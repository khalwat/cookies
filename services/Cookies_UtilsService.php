<?php
namespace Craft;

class Cookies_UtilsService extends BaseApplicationComponent
{

/* --------------------------------------------------------------------------------
	Standard cookies
-------------------------------------------------------------------------------- */

    public function set($name = "", $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
		$expire = (int) $expire;
		setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
		$_COOKIE[$name] = $value;
     } /* -- set */

    public function get($name = "")
    {
		if(isset($_COOKIE[$name]))
			return $_COOKIE[$name];
    } /* -- get */

/* --------------------------------------------------------------------------------
	Security validated cookies
-------------------------------------------------------------------------------- */

    public function setSecure($name = "", $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
		$expire = (int) $expire;
		$cookie = new HttpCookie($name, '');
	
		$cookie->value = craft()->security->hashData(base64_encode(serialize($value)));
		$cookie->expire = $expire;
		$cookie->path = $path;
		$cookie->domain = $domain;
		$cookie->secure = $secure;
		$cookie->httpOnly = $httponly;

		craft()->request->getCookies()->add($cookie->name, $cookie);
    } /* -- setSecure */

    public function getSecure($name = "")
    {
		$cookie = craft()->request->getCookie($name);
		if ($cookie && !empty($cookie->value) && ($data = craft()->security->validateData($cookie->value)) !== false)
		{
			return @unserialize(base64_decode($data));
		}
    } /* -- getSecure */

} /* -- Cookies_UtilsService */