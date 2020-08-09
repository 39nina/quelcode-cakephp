<?php
namespace App\Controller;

use App\Controller\AppController;

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

    public function index()
    {
        // 評価一覧への表示内容を設定
        $authuser_id = $this->Auth->user()['id'];
        $reviews = $this->Ratings->find('all')
            ->where(['rate_target_id'=>$authuser_id])
            ->contain(['Biditems', 'Users'])
            ->order(['Ratings.id'=>'desc']);
        $this->paginate = ['limit' => 10];
        $reviews = $this->paginate($reviews);
        $this->set(compact('reviews','authuser_id'));

        //平均評価を設定
        $all_rate = $this->Ratings->find()
            ->where(['rate_target_id'=>$authuser_id]);
        $avg = round(collection($all_rate)->avg('rate'), 1);
        $this->set(compact('avg'));
    }

}
