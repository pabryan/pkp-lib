<?php

/**
 * @file classes/submission/SubmissionKeywordEntryDAO.inc.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2000-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SubmissionKeywordEntryDAO
 * @ingroup submission
 * @see Submission
 *
 * @brief Operations for retrieving and modifying a submission's keywords
 */

import('lib.pkp.classes.submission.SubmissionKeyword');
import('lib.pkp.classes.controlledVocab.ControlledVocabEntryDAO');

class SubmissionKeywordEntryDAO extends ControlledVocabEntryDAO {
	/**
	 * Constructor
	 */
	function SubmissionKeywordEntryDAO() {
		parent::ControlledVocabEntryDAO();
	}

	/**
	 * Construct a new data object corresponding to this DAO.
	 * @return PaperTypeEntry
	 */
	function newDataObject() {
		return new SubmissionKeyword();
	}

	/**
	 * Retrieve an iterator of controlled vocabulary entries matching a
	 * particular controlled vocabulary ID.
	 * @param $controlledVocabId int
	 * @return object DAOResultFactory containing matching CVE objects
	 */
	function getByControlledVocabId($controlledVocabId, $rangeInfo = null) {
		$result = $this->retrieveRange(
			'SELECT cve.* FROM controlled_vocab_entries cve WHERE cve.controlled_vocab_id = ? ORDER BY seq',
			array((int) $controlledVocabId),
			$rangeInfo
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}
}

?>
