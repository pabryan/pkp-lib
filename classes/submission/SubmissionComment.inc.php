<?php

/**
 * @file classes/submission/SubmissionComment.inc.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SubmissionComment
 * @ingroup submission
 * @see SubmissionCommentDAO
 *
 * @brief Class for SubmissionComment.
 */

/** Comment associative types. All types must be defined here. */
define('COMMENT_TYPE_PEER_REVIEW', 0x01);
define('COMMENT_TYPE_EDITOR_DECISION', 0x02);
define('COMMENT_TYPE_COPYEDIT', 0x03);
define('COMMENT_TYPE_LAYOUT', 0x04);
define('COMMENT_TYPE_PROOFREAD', 0x05);

class SubmissionComment extends DataObject {
	/**
	 * Constructor.
	 */
	function SubmissionComment() {
		parent::DataObject();
	}

	/**
	 * get comment type
	 * @return int COMMENT_TYPE_...
	 */
	function getCommentType() {
		return $this->getData('commentType');
	}

	/**
	 * set comment type
	 * @param $commentType int COMMENT_TYPE_...
	 */
	function setCommentType($commentType) {
		return $this->setData('commentType', $commentType);
	}

	/**
	 * get role id
	 * @return int
	 */
	function getRoleId() {
		return $this->getData('roleId');
	}

	/**
	 * set role id
	 * @param $roleId int
	 */
	function setRoleId($roleId) {
		return $this->setData('roleId', $roleId);
	}

	/**
	 * get role name
	 * @return string
	 */
	function getRoleName() {
		$role = new Role($this->getData('roleId'));
		return $role->getRoleName();
	}

	/**
	 * get submission id
	 * @return int
	 */
	function getSubmissionId() {
		return $this->getData('submissionId');
	}

	/**
	 * set submission id
	 * @param $submissionId int
	 */
	function setSubmissionId($submissionId) {
		return $this->setData('submissionId', $submissionId);
	}

	/**
	 * get assoc id
	 * @return int
	 */
	function getAssocId() {
		return $this->getData('assocId');
	}

	/**
	 * set assoc id
	 * @param $assocId int
	 */
	function setAssocId($assocId) {
		return $this->setData('assocId', $assocId);
	}

	/**
	 * get author id
	 * @return int
	 */
	function getAuthorId() {
		return $this->getData('authorId');
	}

	/**
	 * set author id
	 * @param $authorId int
	 */
	function setAuthorId($authorId) {
		return $this->setData('authorId', $authorId);
	}

	/**
	 * get author name
	 * @return string
	 */
	function getAuthorName() {
		// Reference used to set if not already fetched
		$authorFullName =& $this->getData('authorFullName');

		if(!isset($authorFullName)) {
			$userDao = DAORegistry::getDAO('UserDAO');
			$authorFullName = $userDao->getUserFullName($this->getAuthorId(), true);
		}

		return $authorFullName ? $authorFullName : '';
	}

	/**
	 * get author email
	 * @return string
	 */
	function getAuthorEmail() {
		// Reference used to set if not already fetched
		$authorEmail =& $this->getData('authorEmail');

		if(!isset($authorEmail)) {
			$userDao = DAORegistry::getDAO('UserDAO');
			$authorEmail = $userDao->getUserEmail($this->getAuthorId(), true);
		}

		return $authorEmail ? $authorEmail : '';
	}

	/**
	 * get comment title
	 * @return string
	 */
	function getCommentTitle() {
		return $this->getData('commentTitle');
	}

	/**
	 * set comment title
	 * @param $commentTitle string
	 */
	function setCommentTitle($commentTitle) {
		return $this->setData('commentTitle', $commentTitle);
	}

	/**
	 * get comments
	 * @return string
	 */
	function getComments() {
		return $this->getData('comments');
	}

	/**
	 * set comments
	 * @param $comments string
	 */
	function setComments($comments) {
		return $this->setData('comments', $comments);
	}

	/**
	 * get date posted
	 * @return date
	 */
	function getDatePosted() {
		return $this->getData('datePosted');
	}

	/**
	 * set date posted
	 * @param $datePosted date
	 */
	function setDatePosted($datePosted) {
		return $this->setData('datePosted', $datePosted);
	}

	/**
	 * get date modified
	 * @return date
	 */
	function getDateModified() {
		return $this->getData('dateModified');
	}

	/**
	 * set date modified
	 * @param $dateModified date
	 */
	function setDateModified($dateModified) {
		return $this->setData('dateModified', $dateModified);
	}

	/**
	 * get viewable
	 * @return boolean
	 */
	function getViewable() {
		return $this->getData('viewable');
	}

	/**
	 * set viewable
	 * @param $viewable boolean
	 */
	function setViewable($viewable) {
		return $this->setData('viewable', $viewable);
	}
}

?>
