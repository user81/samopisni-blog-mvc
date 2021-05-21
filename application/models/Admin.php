<?php 

namespace application\models;
use application\core\Model;
use Imagick;

class Admin extends Model
{

  public $error; 

  public function loginValidate($post)
  {
    $config = require 'application/config/admin.php';
    
    if ( $config['login'] !=$post['login'] || $config['password'] !=$post['password']) {
      $this->error = $config['login'].' '.$config['password']; 
    return false;
    }
    return true;
  }

  public function postValidate($post, $type) {
		$nameLen = iconv_strlen($post['name']);
		$descriptionLen = iconv_strlen($post['description']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 100) {
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		} elseif ($descriptionLen < 3 or $descriptionLen > 100) {
			$this->error = 'Описание должно содержать от 3 до 100 символов';
			return false;
		} elseif ($textLen < 10 or $textLen > 5000) {
			$this->error = 'Текст должнен содержать от 10 до 5000 символов';
			return false;
		}
/* 		if (empty($_FILES['img']['tmp_name']) and $type == 'add') {
			$this->error = 'Изображение не выбрано';
			return false;
		} */
		return true;
	}

	public function postAdd($post) {
		$params = [
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->db->query('INSERT INTO `posts` (`name`, `description`, `text` ) VALUES ( :name, :description, :text)', $params);
		return $this->db->lastInsertId();
	}

	public function postEdit($post, $id) {
		$params = [
			'id' => $id,
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->db->query('UPDATE posts SET name = :name, description = :description, text = :text WHERE id = :id', $params);
	}

	public function postUploadImage($path, $id)
	{
		//сжатие картинки
/* 		$img = new Imagick($path); */
		// размер 1080 600
	/* 	$img->cropThumbnailImage(1080, 600); */
		// качество 80
/* 		$img->setImageCompressionQuality(80);
		$img->writeImage('public/materials/'.$id.'.jpg'); */
				move_uploaded_file($path, 'public/materials/'.$id.'.jpg');
	} 

	public function isPostExists($id)
	{
		$params = [
			'id'=>$id,
		];
		return $this->db->column('SELECT id FROM posts WHERE id = :id', $params);
	}

	public function postDelete($id) 
	{
		$params = [
			'id' => $id,
		];
		$this->db->query('DELETE FROM posts WHERE id = :id', $params);
		unlink('public/materials/'.$id.'.jpg');
	}


	public function postData($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->row('SELECT * FROM posts WHERE id = :id', $params);
	}
	

}
