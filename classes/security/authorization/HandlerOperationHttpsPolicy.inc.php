<?php
/**
 * @file classes/security/authorization/HandlerOperationHttpsPolicy.inc.php
 *
 * Copyright (c) 2000-2010 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class HandlerOperationHttpsPolicy
 * @ingroup security_authorization
 *
 * @brief Class to control access to handler operations based on protocol.
 */

import('lib.pkp.classes.security.authorization.AuthorizationPolicy');

class HandlerOperationHttpsPolicy extends AuthorizationPolicy {
	/** @var PKPRequest */
	var $_request;

	/**
	 * Constructor
	 *
	 * @param $request PKPRequest
	 */
	function HandlerOperationHttpsPolicy(&$request) {
		parent::AuthorizationPolicy();
		$this->_request =& $request;

		// Add advice
		$application =& PKPApplication::getApplication();
		$request =& $application->getRequest();
		$callOnDeny = array($request, 'redirectSSL', array());
		$this->setAdvice(AUTHORIZATION_ADVICE_CALL_ON_DENY, $callOnDeny);
	}

	//
	// Implement template methods from AuthorizationPolicy
	//
	/**
	 * @see AuthorizationPolicy::applies()
	 */
	function applies() {
		return (boolean)Config::getVar('security', 'force_ssl');
	}

	/**
	 * @see AuthorizationPolicy::effect()
	 */
	function effect() {
		// Check the request protocol
		if ($this->_request->getProtocol() == 'https') {
			return AUTHORIZATiON_ALLOW;
		} else {
			return AUTHORIZATION_DENY;
		}
	}
}

?>
