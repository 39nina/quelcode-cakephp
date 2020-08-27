<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event; // added.
use Exception; // added.
use Symfony\Component\VarDumper\VarDumper;

class AuctionController extends AuctionBaseController
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

	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' =>['endtime'=>'desc'],
			'limit' => 10]);
		$this->set(compact('auction'));
	}

	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions'=>['biditem_id'=>$id],
				'contain' => ['Users'],
				'order'=>['price'=>'desc']])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)){
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions'=>['biditem_id'=>$id],
			'contain' => ['Users'],
			'order'=>['price'=>'desc']])->toArray();
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests'));

		//カウントダウンタイマー用にjsファイルに値を渡す
			$endtime = strtotime($biditem->endtime);
			$now = time();
			$this->set(compact('endtime'));
			$this->set(compact('now'));

		//ログイン者のidを$login_idとして設定
		$this->set('login_id', $this->Auth->user('id'));
	}

	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			$image = $this->request->getData(['image_path']);
			$imagename = $image['name'];
			// 画像の拡張子が適当か確認
			$str = substr($imagename, -5);
			if (
				(strpos($str, '.jpg')!== false) ||
				(strpos($str, '.jpeg')!== false) ||
				(strpos($str, '.png')!== false) ||
				(strpos($str, '.gif')!== false) ||
				(strpos($str, '.JPG')!== false) ||
				(strpos($str, '.JPEG')!== false) ||
				(strpos($str, '.PNG')!== false) ||
				(strpos($str, '.GIF')!== false)
			) {
				// 拡張子が適切なら画像を保存
				// 画像名連番の最後の数（最大値）をDBから取得し、その次の数を画像に命名
				$find = $this->Biditems->find()
					->order(['id' => 'desc'])
					->first();
				$imageid = ($find['id'] + 1);
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
				$imagePath = 'img/auction/' .$imageid . "." .$ext;
				move_uploaded_file($image['tmp_name'], $imagePath);
				// $biditemにフォームの送信内容を反映
				$biditem = $this->Biditems->patchEntity($biditem, $this->request->getData());
				// $biditemのimage_pathを修正
				$biditem['image_path'] = $imagePath;
				// $biditemを保存する
				if ($this->Biditems->save($biditem)) {
					// 成功時のメッセージ
					$this->Flash->success(__('保存しました。'));
					// トップページ（index）に移動
					return $this->redirect(['action' => 'index']);
				}
				// 失敗時のメッセージ
				$this->Flash->error(__('保存に失敗しました。もう一度入力ください。'));
			} else {
				// 画像拡張子が適切でなかった時のメッセージ
				$this->Flash->error(__('登録できないファイルです。jpg, jpeg, png, gif, JPG, JPEG, PNG, GIFのいずれかの形式の画像を登録してください。'));
			}
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $bidrequestに送信フォームの内容を反映する
			$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $this->request->getData());
			// Bidrequestを保存
			if ($this->Bidrequests->save($bidrequest)) {
				// 成功時のメッセージ
				$this->Flash->success(__('入札を送信しました。'));
				// トップページにリダイレクト
				return $this->redirect(['action'=>'view', $biditem_id]);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。'));
		}
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		$this->set(compact('bidrequest', 'biditem'));
	}

	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain'=>['Biditems']]);
		} catch(Exception $e){
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all',[
			'conditions'=>['bidinfo_id'=>$bidinfo_id],
			'contain' => ['Users'],
			'order'=>['created'=>'desc']]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}

	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions'=>['Bidinfo.user_id'=>$this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order'=>['created'=>'desc'],
			'limit' => 10])->toArray();
		$this->set(compact('bidinfo'));
	}

	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions'=>['Biditems.user_id'=>$this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order'=>['created'=>'desc'],
			'limit' => 10])->toArray();
		$this->set(compact('biditems'));
	}

	// 落札者とのメッセージ
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

		// 落札者idと出品者idを用意
		$bidder_id = $bidinfo->user_id;
		$exhibitor_id = $bidinfo->biditem->user_id;
		$this->set(compact('bidder_id', 'exhibitor_id'));

		// 取引連絡開始用に落札者の発送先情報連絡フォームを用意
		// このオークションの取引連絡レコードが作られている場合、$contactEntityを設定
		$biditem_id = $bidinfo->biditem_id;
		$bidinfo_id = $bidinfo->id;
		try {
			$contactEntity = $this->Contacts->find('all',
				 ['conditions'=>['biditem_id'=>$biditem_id]])->first();
		} catch(Exception $e) {
			$contactEntity = null;
		}
		$this->set(compact('contactEntity'));

		$contact = $this->Contacts->newEntity();
		// $contact（落札者情報）がPOSTされた時の処理
		if (!empty($this->request->getData('name'))) {
			// 送信されたフォーム内容、オークションidで$contactを更新、落札者情報フラグに1をセット
			$contact = $this->Contacts->patchEntity($contact, $this->request->getData());
			$contact->biditem_id =  $bidinfo->biditem_id;
			$contact->sent_info = 1;
			// Contactを保存
			if ($this->Contacts->save($contact)) {
				// 送信できたら、連絡先情報フォームを表示させないようにするため、$contactEntityに内容をセット
				$contactEntity = $this->Contacts->find('all',
				['conditions'=>['biditem_id'=>$biditem_id]])->first();
				$this->set(compact('contactEntity'));
				//重複送信防止
				header('Location: ./' . $bidinfo_id);
			} else {
				$this->Flash->error(__('送信に失敗しました。もう一度入力下さい。'));
			}
		}

		// 発送連絡がPOSTされた時の処理
		if (!empty($this->request->getData('is_shipped'))) {
			// 発送連絡フラグ（is_shipped）に1をセット
			$contactEntity->is_shipped = 1;
			// Contactを保存
			if ($this->Contacts->save($contactEntity)) {
				//重複送信防止
				header('Location: ./' . $bidinfo_id);
			} else {
				$this->Flash->error(__('送信に失敗しました。もう一度押して下さい。'));
			}
		}

		// 出品者が発送完了している場合、評価機能用のRatingsコントローラーに遷移
		if ($contactEntity['is_shipped'] === true) {
			$id = $bidinfo['id'];
			return $this->redirect(
				['controller' => 'Ratings', 'action' => 'contact', $id]
			);
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
