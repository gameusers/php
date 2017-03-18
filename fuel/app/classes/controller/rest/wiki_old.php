<?php

class Controller_Rest_Wiki extends Controller_Rest_Base
{
	
	
	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------
	
	private $search_type = null;
	private $game_data_id = null;
	
	
	// ------------------------------
	//   インスタンス
	// ------------------------------
	
	private $model_gc = null;
	private $model_advertisement = null;
	
	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;
	
	private $original_code_advertisement = null;
	
	
	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
		
		
		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------
		
		$this->model_gc = new Model_Gc();
		$this->model_gc->agent_type = $this->agent_type;
		$this->model_gc->user_no = $this->user_no;
		$this->model_gc->language = $this->language;
		$this->model_gc->uri_base = $this->uri_base;
		$this->model_gc->uri_current = $this->uri_current;
		
		$this->model_advertisement = new Model_Advertisement();
		
		$this->original_validation_common = new Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		
		$this->original_code_advertisement = new \Original\Code\Advertisement();
		
	}
	
	
	
	
	/**
	* Setter / search_type
	*
	* @param integer $argument
	*/
	/*
	public function set_search_type($argument)
	{
		if ($argument)
		{
			$this->original_validation_fieldsetex->reset();
			
			$val = \Validation::forge();
			$val->add_field('search_type', 'Search Type', 'required|valid_string[numeric]|numeric_between[1,5]');
			
			if ($val->run(array('search_type' => $argument)))
			{
				$this->search_type = (int) $val->validated('search_type');
			}
			else
			{
				$this->original_validation_common->throw_exception($val->error());
			}
		}
		else
		{
			$this->search_type = null;
		}
	}
	
	public function get_search_type()
	{
		return $this->search_type;
	}
	*/
	
	
	
	/**
	* Setter / game_data_id
	*
	* @param string $argument
	*/
	public function set_game_data_id($argument)
	{
		
		if ($argument)
		{
			$this->game_data_id = $this->original_validation_common->game_data_id($argument);
		}
		else
		{
			$this->game_data_id = null;
		}
		
	}
	
	public function get_game_data_id()
	{
		return $this->game_data_id;
	}
	
	
	
	
	/**
	* スレッド一覧読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_code()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['game_data_id'] = 'hearthstone';
			//$_POST['search_type'] = 1;
		}
		
		
		$arr = array();
		
		try
		{
			
			$language = 'ja';
			
			$this->set_game_data_id(Input::post('game_data_id'));
			$result_arr = $this->model_gc->get_wiki_read_bbs($this->get_game_data_id(), $language);
			
			$gc_base_url = URI_BASE . 'gc/' . Security::htmlentities(Input::post('game_data_id'));
			$result_arr = Security::htmlentities($result_arr);
			
			
			// 交流掲示板
			$code = '<p><a href="' . $gc_base_url . '">交流掲示板</a></p>' . "\n";
			$code .= '<ul>' . "\n";
			foreach ($result_arr['bbs_list_arr'] as $key => $value)
			{
				$code .= '<li><a href="' . $gc_base_url . '/bbs/' . $value['bbs_thread_no'] . '">' . $value['title'] . '</a> (' . $value['comment_total'] . ')</li>' . "\n";
			}
			$code .= '</ul><br>' . "\n";
			
			
			// 募集掲示板
			$code .= '<p><a href="' . $gc_base_url . '/rec">募集掲示板</a></p>' . "\n";
			$code .= '<ul>' . "\n";
			if ($result_arr['type1']) $code .= '<li><a href="' . $gc_base_url . '/rec/1">プレイヤー</a> (' . $result_arr['type1'] . ')</li>' . "\n";
			if ($result_arr['type2']) $code .= '<li><a href="' . $gc_base_url . '/rec/2">フレンド</a> (' . $result_arr['type2'] . ')</li>' . "\n";
			if ($result_arr['type3']) $code .= '<li><a href="' . $gc_base_url . '/rec/3">ギルド・クランメンバー</a> (' . $result_arr['type3'] . ')</li>' . "\n";
			if ($result_arr['type4']) $code .= '<li><a href="' . $gc_base_url . '/rec/4">売買・交換相手</a> (' . $result_arr['type4'] . ')</li>' . "\n";
			if ($result_arr['type5']) $code .= '<li><a href="' . $gc_base_url . '/rec/5">その他</a> (' . $result_arr['type5'] . ')</li>' . "\n";
			$code .= '</ul>' . "\n";
			
			
			$arr['code'] = $code;
			
			
		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}
		
		
		// --------------------------------------------------
		//   出力
		// --------------------------------------------------
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* 広告読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_advertisement_code()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['game_data_id'] = 'hearthstone';
		}
		
		
		$arr = array();
		
		try
		{
			
			
			// データ取得
			$this->set_game_data_id(Input::post('game_data_id'));
			$temp_arr = array('game_data_id', $this->get_game_data_id());
			$result_arr = $this->model_advertisement->get_advertisement($temp_arr);
			
			// 処理
			$default_arr = array();
			$user_arr = array();
			
			foreach ($result_arr as $key => $value)
			{
				if ($value['administration'] and $value['ad_default'])
				{
					if (AGENT_TYPE != 'smartphone')
					{
						($value['code']) ? $default_arr[$value['name']] = $value['code'] : $default_arr[$value['name']] = $value['code_sp'];
					}
					else
					{
						($value['code_sp']) ? $default_arr[$value['name']] = $value['code_sp'] : $default_arr[$value['name']] = $value['code'];
					}
				}
				else
				{
					if (AGENT_TYPE != 'smartphone')
					{
						($value['code']) ? $user_arr[$value['name']] = $value['code'] : $user_arr[$value['name']] = $value['code_sp'];
					}
					else
					{
						($value['code_sp']) ? $user_arr[$value['name']] = $value['code_sp'] : $user_arr[$value['name']] = $value['code'];
					}
				}
			}
			
			
			$arr['default_arr'] = (count($default_arr) > 0) ? $default_arr : null;
			$arr['user_arr'] = (count($user_arr) > 0) ? $user_arr : null;
			
			
			
			
			// --------------------------------------------------
			//   Amazonスライド広告
			// --------------------------------------------------
			
			$code_ad_amazon_slide = $this->original_code_advertisement->code_ad_amazon_slide();
			
			$arr['amazon_slide'] = $code_ad_amazon_slide;
			
			
			
			// --------------------------------------------------
			//   広告を表示しない
			// --------------------------------------------------
			
			if (AD_BLOCK)
			{
				$arr['default_arr'] = null;
				$arr['user_arr'] = null;
			}
			
			
			
			if (isset($test))
			{
				
				\Debug::$js_toggle_open = true;
				
				echo '$result_arr';
				\Debug::dump($result_arr);
				
				echo '$default_arr';
				\Debug::dump($default_arr);
				
				echo '$user_arr';
				\Debug::dump($user_arr);
				
				exit();
				
			}
			
		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}
		
		
		// --------------------------------------------------
		//   出力
		// --------------------------------------------------
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
}