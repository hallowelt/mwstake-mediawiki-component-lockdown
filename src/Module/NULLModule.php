<?php

namespace MWStake\MediaWiki\Component\Lockdown\Module;

use Message;
use MWStake\MediaWiki\Component\Lockdown\Module;
use RawMessage;
use Title;
use User;

class NULLModule extends Module {
	/**
	 * @param Title $title
	 * @param User $user
	 * @return bool
	 */
	public function applies( Title $title, User $user ): bool {
		return false;
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @param type $action
	 * @return Message
	 */
	public function getLockdownReason( Title $title, User $user, $action ): Message {
		return RawMessage( 'NULL module' );
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @param type $action
	 * @return bool
	 */
	public function mustLockdown( Title $title, User $user, $action ): bool {
		return false;
	}

}
