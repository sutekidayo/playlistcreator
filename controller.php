<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
class PlaylistCreatorBlockController extends BlockController {
	
	protected $btTable = "playlistcreator";
	protected $btInterfaceWidth = "350";
	protected $btInterfaceHeight = "300";

	public function getBlockTypeName() {
		return t('Social Playlist Creator');
	}

	public function getBlockTypeDescription() {
		return t('Allows youth to pick and vote on songs they would like to hear at the next dance!');
	}
}
