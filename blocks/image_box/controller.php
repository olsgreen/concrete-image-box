<?php
/**
 * Block Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
namespace Concrete\Package\ImageBoxBlock\Block\ImageBox;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Core\Legacy\Loader;
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;

/**
 * Block Controller Class.
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
class Controller extends BlockController
{
    /**
     * Block table name.
     *
     * @var string
     */
	protected $btTable = 'btImageBox';

	/**
     * Block editor interface width.
     *
     * @var string
     */
	protected $btInterfaceWidth = "340";

	/**
     * Block editor interface height.
     *
     * @var string
     */
	protected $btInterfaceHeight = "365";

	/**
     * Cache the blocks database record?
     *
     * @var bool
     */
	protected $btCacheBlockRecord = true;

	/**
     * Cache the blocks output?
     *
     * @var bool
     */
	protected $btCacheBlockOutput = true;

    /**
     * Cache the block output for $_POST requests?
     *
     * @var bool
     */
	protected $btCacheBlockOutputOnPost = true;

    /**
     * Cache the blocks output for registered users?
     *
     * @var bool
     */
	protected $btCacheBlockOutputForRegisteredUsers = false;

	/**
     * How long do we cache the block for?
     *
     * CACHE_LIFETIME = Until manually cleared or the
     * block is updated via the editor.
     *
     * @var int
     */
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME; //until manually updated or cleared

    /**
     * The set within the block chooser interface
     * that this block belongs to.
     *
     * @var string
     */
	protected $btDefaultSet = 'basic';

	/**
	 * Get the blocks name.
	 *
	 * @return string
	 */
	public function getBlockTypeName()
	{
		return t("Image box");
	}

	/**
	 * Get the blocks description.
	 *
	 * @return string
	 */
	public function getBlockTypeDescription()
	{
		return t("Simple image, text & link units.");
	}

	/**
	 * Add form hook.
	 *
	 * @return  void
	 */
	public function add()
	{
		$this->form();
	}

	/**
	 * Edit form hook.
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->form();
	}

	/**
	 * Set the data for blocks form.
	 *
	 * @return void
	 */
	public function form()
	{
		$form = Loader::helper('form');
		$ps = Loader::helper('form/page_selector');
		$al = Loader::helper('concrete/asset_library');

		$this->set('image_file', $this->getImageFileObject());
		$this->set('page_selector', $ps);
		$this->set('asset_library', $al);
		$this->set('form', $form);
	}

    /**
     * Save the block record.
     *
     * @param  array $args
     * @return boolean
     */
    public function save($args)
    {
        if ('none' === $args['link_type']) {
            $args['linkCID'] = 0;
            $args['link'] = '';
            $args['button_text'] = '';
        }

        return parent::save($args);
    }

	/**
	 * Get the file object associated with the block.
	 *
	 * @return mixed
	 */
	public function getImageFileObject()
	{
		return $this->fID > 0 ? File::getByID($this->fID) : null;
	}

	/**
	 * Get a formatted url for the resource the block links to.
	 *
	 * @return string
	 */
	public function getLinkUrl()
	{
		if ('page_selector' !== $this->link_type) {
			return $this->link;
		} else {
			$path = Page::getByID($this->linkCID)->getCollectionPath();

			return View::url($path ?: '/');
		}
	}

	/**
	 * Get the associated images URL.
	 *
	 * @return string
	 */
	public function getImageUrl()
	{
		if ($this->fID > 0)
		{
			$f = $this->getImageFileObject()->getRecentVersion();
			return $f->getUrl();
		}

		return '';
	}
}
