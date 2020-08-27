<?php
namespace App\Controller;

use App\Controller\AppController;
use Exception;

class RatingsController extends AuctionBaseController
{
	// デフォルトテーブルを使わない
	public $useTable = false;

	// 初期化処理
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		// 必要なモデルをすべてロード
		$this->loadModel('Users');
		$this->loadModel('Biditems');
		$this->loadModel('Bidrequests');
		$this->loadModel('Bidinfo');
		$this->loadModel('Bidmessages');
		$this->loadModel('Contacts');
		$this->loadModel('Ratings');
		// ログインしているユーザー情報をauthuserに設定
		$this->set('authuser', $this->Auth->user());
		// レイアウトをauctionに変更
		$this->viewBuilder()->setLayout('auction');
    }

    public function rating()
    {
        // 評価一覧への表示内容を設定
        $authuser_id = $this->Auth->user()['id'];
        $reviews = $this->Ratings->find('all')
            ->where(['rate_target_id'=>$authuser_id])
            ->contain(['Biditems', 'RaterUsers'])
            ->order(['Ratings.id'=>'desc']);
        $this->paginate = ['limit' => 10];
        $reviews = $this->paginate($reviews);
        $this->set(compact('reviews','authuser_id'));

		//平均評価を設定
        $avg = round(collection($reviews)->avg('rate'), 1);
        $this->set(compact('avg'));
    }

	public function contact($bidinfo_id = null)
	{
		// $bidinfo_idからBidinfoを取得する
		try {
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain'=>['Biditems']]);
		} catch(Exception $e){
			return $this->redirect(
				['controller' => 'Auction', 'action' => 'index']
			);
		}
		$bidinfo_id = $bidinfo->id;

		// 落札者idと出品者idを用意
		$bidder_id = $bidinfo->user_id;
		$exhibitor_id = $bidinfo->biditem->user_id;
		$this->set(compact('bidder_id', 'exhibitor_id'));

		// 取引連絡開始用に落札者の発送先情報連絡フォームを用意
		// このオークションの取引連絡レコードが作られている場合、$contactEntityを設定
		$biditem_id = $bidinfo->biditem_id;
		try {
			$contactEntity = $this->Contacts->find('all',
				 ['conditions'=>['biditem_id'=>$biditem_id]])->first();
		} catch(Exception $e) {
			$contactEntity = null;
		}
		$this->set(compact('contactEntity'));

		// 落札者から受け取り評価連絡がPOSTされた場合
		if (!empty($this->request->getData('rate'))) {
			// ratingsテーブルに新レコードを作成する
			$rating = $this->Ratings->newEntity();
			// 送信された内容でエンティティを更新、追加情報を$ratingにセット
			$rating = $this->Ratings->patchEntity($rating, $this->request->getData());
			$rating->biditem_id = $bidinfo['biditem_id'];
			$rating->rater_id = $bidder_id;
			$rating->rate_target_id = $exhibitor_id;
			$rating->rate = $this->request->getData('rate');
			$rating->comment = $this->request->getData('comment');
			// 落札者からの評価フラグに1をセット
			$contactEntity->is_rated_by_bidder = 1;
			// $Ratingを保存
			if ($this->Ratings->save($rating) && $this->Contacts->save($contactEntity)) {
				//重複送信防止
				header('Location: ./' . $bidinfo_id);
			} else {
				$this->Flash->error(__('送信に失敗しました。'));
			}
		}

		// 出品者から受け取り評価連絡がPOSTされた場合
		if (!empty($this->request->getData('rate2'))) {
			// ratingsテーブルに新レコードを作成する
			$rating2 = $this->Ratings->newEntity();
			// 送信された内容でエンティティを更新、追加情報を$ratingにセット
			$rating2 = $this->Ratings->patchEntity($rating2, $this->request->getData());
			$rating2->biditem_id = $bidinfo['biditem_id'];
			$rating2->rater_id = $exhibitor_id;
			$rating2->rate_target_id = $bidder_id;
			$rating2->rate = $this->request->getData('rate2');
			$rating2->comment = $this->request->getData('comment');
			// 落札者からの評価フラグに1をセット
			$contactEntity->is_rated_by_exhibitor = 1;
			// $Ratingを保存
			if ($this->Ratings->save($rating2) && $this->Contacts->save($contactEntity)) {
				//重複送信防止
				header('Location: ./' . $bidinfo_id);
			} else {
				$this->Flash->error(__('送信に失敗しました。'));
			}
		}

		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// $bidmsg（メッセージ）がPOSTされた時の処理
		if ($this->request->is('post') && !empty($this->request->getData('message'))) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				//重複送信防止
				header('Location: ./' . $bidinfo_id);
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all',[
			'conditions'=>['bidinfo_id'=>$bidinfo_id],
			'contain' => ['Users'],
			'order'=>['created'=>'desc']]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}
}
