<?php
/**
 * @desc 用户模型
*/
namespace Common\Model;
class EncryptModel
{
	/**
	 * @desc 加密用户密码
	 * @param string $password  密码
	 * @return string
	*/
	static public function enpass($password)
	{
		return crypt($password, ENCRYPT_KEY);
	}
}