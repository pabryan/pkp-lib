<?php

/**
 * @file controllers/grid/users/reviewer/form/ThankReviewerForm.inc.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ThankReviewerForm
 * @ingroup controllers_grid_users_reviewer_form
 *
 * @brief Form for sending a thank you to a reviewer
 */

import('lib.pkp.classes.form.Form');

class ThankReviewerForm extends Form {
	/** The review assignment associated with the reviewer **/
	var $_reviewAssignment;

	/**
	 * Constructor.
	 */
	function ThankReviewerForm($reviewAssignment) {
		parent::Form('controllers/grid/users/reviewer/form/thankReviewerForm.tpl');
		$this->_reviewAssignment = $reviewAssignment;

		// Validation checks for this form
		$this->addCheck(new FormValidatorPost($this));
	}

	//
	// Getters and Setters
	//
	/**
	 * Get the review assignment
	 * @return ReviewAssignment
	 */
	function getReviewAssignment() {
		return $this->_reviewAssignment;
	}

	//
	// Overridden template methods
	//
	/**
	 * Initialize form data from the associated author.
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function initData($args, $request) {
		$userDao = DAORegistry::getDAO('UserDAO');
		$user = $request->getUser();
		$context = $request->getContext();

		$reviewAssignment = $this->getReviewAssignment();
		$reviewerId = $reviewAssignment->getReviewerId();
		$reviewer = $userDao->getById($reviewerId);

		$submissionDao = Application::getSubmissionDAO();
		$submission = $submissionDao->getById($reviewAssignment->getSubmissionId());

		import('lib.pkp.classes.mail.SubmissionMailTemplate');
		$email = new SubmissionMailTemplate($submission, 'REVIEW_ACK');

		$dispatcher = $request->getDispatcher();
		$paramArray = array(
			'reviewerName' => $reviewer->getFullName(),
			'editorialContactSignature' => $user->getContactSignature(),
			'reviewerUserName' => $reviewer->getUsername(),
			'passwordResetUrl' => $dispatcher->url($request, ROUTE_PAGE, null, 'login', 'resetPassword', $reviewer->getUsername(), array('confirm' => Validation::generatePasswordResetHash($reviewer->getId()))),
			'submissionReviewUrl' => $dispatcher->url($request, ROUTE_PAGE, null, 'reviewer', 'submission', null, array('submissionId' => $reviewAssignment->getSubmissionId()))
		);
		$email->assignParams($paramArray);

		$this->setData('submissionId', $submission->getId());
		$this->setData('stageId', $reviewAssignment->getStageId());
		$this->setData('reviewAssignmentId', $reviewAssignment->getId());
		$this->setData('reviewAssignment', $reviewAssignment);
		$this->setData('reviewerName', $reviewer->getFullName() . ' <' . $reviewer->getEmail() . '>');
		$this->setData('message', $email->getBody() . "\n" . $context->getSetting('emailSignature'));
	}

	/**
	 * Assign form data to user-submitted data.
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array('message', 'skipEmail'));
	}

	/**
	 * Save review assignment
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function execute($args, $request) {
		$userDao = DAORegistry::getDAO('UserDAO');
		$submissionDao = Application::getSubmissionDAO();

		$reviewAssignment = $this->getReviewAssignment();
		$reviewerId = $reviewAssignment->getReviewerId();
		$reviewer = $userDao->getById($reviewerId);
		$submission = $submissionDao->getById($reviewAssignment->getSubmissionId());

		import('lib.pkp.classes.mail.SubmissionMailTemplate');
		$email = new SubmissionMailTemplate($submission, 'REVIEW_ACK', null, null, null, false);

		$email->addRecipient($reviewer->getEmail(), $reviewer->getFullName());
		$email->setBody($this->getData('message'));

		if (!$this->getData('skipEmail')) {
			HookRegistry::call('ThankReviewerForm::thankReviewer', array(&$submission, &$reviewAssignment, &$email));
			$email->send($request);
		}

		// update the ReviewAssignment with the acknowledged date
		$reviewAssignmentDao = DAORegistry::getDAO('ReviewAssignmentDAO');
		$reviewAssignment->setDateAcknowledged(Core::getCurrentDate());
		$reviewAssignment->stampModified();
		$reviewAssignment->setUnconsidered(REVIEW_ASSIGNMENT_NOT_UNCONSIDERED);
		$reviewAssignmentDao->updateObject($reviewAssignment);
	}
}

?>
