<?php
class ElectronicItems
{
	private $items = array();

	public function __construct(array $items)
	{
		$this->items = $items;
	}

	/**
	 * Returns the items depending on the sorting type requested
	 *
	 * @return array
	 */
	public function getSortedItems($type)
	{
		$sorted = array();
		foreach ($this->items as $key => $item)
		{
			$sorted[($item->price * 100)] = $item;
		}

		ksort($sorted, SORT_NUMERIC);
		return $sorted;
	}
	/**
	 *
	 * @param string $type
	 * @return array
	 */
	public function getItemsByType($type)
	{
		if (in_array($type, ElectronicItem::$types))
		{
			$callback = function($item) use ($type)
		{
		return $item->type == $type;
		};
			$items = array_filter($this->items, $callback);
		}
		return false;
	}
}

class ElectronicItem
{	
	protected $extraItemsList = array();
	protected $maxNumExtraItems;
	/**
	 * @var float
	 */
	public $price;

	/**
	 * @var string
	 */
	protected $type;
	public $wired;
	const ELECTRONIC_ITEM_TELEVISION = 'television';
	const ELECTRONIC_ITEM_CONSOLE = 'console';
	const ELECTRONIC_ITEM_MICROWAVE = 'microwave';
	const ELECTRONIC_ITEM_CONTROLLER = 'controller';

	private static $types = array(self::ELECTRONIC_ITEM_CONSOLE,
	self::ELECTRONIC_ITEM_MICROWAVE, self::ELECTRONIC_ITEM_TELEVISION, self::ELECTRONIC_ITEM_CONTROLLER);

	function getPrice()
	{
		return $this->price;
	}
 
	function getType()
	{
		return $this->type;
	}
 
	function getWired()
	{
		return $this->wired;
	}
 
	function setPrice($price)
	{
		$this->price = $price;
	}
 
	function setType($type)
	{
		$this->type = $type;
	}
 
	function setWired($wired)
	{
		$this->wired = $wired;
	}

	function getExtraItemsList()
	{
		return $this->extraItemsList;
	}

	/**
	 * @return boolean
	 */
	protected function maxExtras()
	{
		if (sizeof($this->extraItemsList) < $maxNumExtraItems)
		{
			return true;
		}

	 	return false;
	}

	/**
	 * Returns the extra items if any
	 *
	 * @return array
	 */
	public function getExtraItems()
	{
		return $this->extraItemsList;
	}
}

class Television extends ElectronicItem {

	public function __construct($price) {
		$this->setType(parent::ELECTRONIC_ITEM_TELEVISION);
		$this->setPrice($price);
		$this->setWired(false);
	}

	/**
	 * @param ElectronicItem $electronicItemObj
	 * @return boolean
	 */
	public function addExtraItem($electronicItemObj)
	{
		array_push( $this->extraItemsList, $electronicItemObj);
		return true;
	}
}

class Console extends ElectronicItem {

	public function __construct($price) {
		$this->setType(parent::ELECTRONIC_ITEM_CONSOLE);
		$this->setPrice($price);
		$this->setWired(false);
		$this->maxNumExtraItems = 4;
	}

	/**
	 *
	 * @param ElectronicItem $electronicItemObj
	 * @return boolean
	 */
	public function addExtraItem($electronicItemObj)
	{
		if(sizeof($this->extraItemsList) < $this->maxNumExtraItems) {
			array_push( $this->extraItemsList, $electronicItemObj);
			return true;
		}
	}

}

class Microwave extends ElectronicItem {

	public function __construct($price) {
		$this->setType(parent::ELECTRONIC_ITEM_MICROWAVE);
		$this->setPrice($price);
		$this->setWired(false);
	}

	/**
	 * @param ElectronicItem $electronicItemObj
	 * @return boolean
	 */
	public function addExtraItem($electronicItemObj)
	{
		return false;
	}
}

class Controller extends ElectronicItem {

	public function __construct( $price, $wired) {
		$this->setType(parent::ELECTRONIC_ITEM_CONTROLLER);
		$this->setPrice($price);
		$this->setWired($wired);
	}

	/**
	 * @param ElectronicItem $electronicItemObj
	 * @return boolean
	 */
	public function addExtraItem($electronicItemObj)
	{
		return false;
	}
}

echo "<b>Q1:</b><br>";
$boughtConsole = new Console(300);
// Creating extra item objects for console
$remoteController1ForConsole = new Controller( 0, false);
$remoteController2ForConsole = new Controller( 0, false);
$wiredController1ForConsole = new Controller( 0, true);
$wiredController2ForConsole = new Controller( 0, true);
// Adding extra items to Console
$boughtConsole->addExtraItem($remoteController1ForConsole);
$boughtConsole->addExtraItem($remoteController2ForConsole);
$boughtConsole->addExtraItem($wiredController1ForConsole);
$boughtConsole->addExtraItem($wiredController2ForConsole);

$boughtTv1 = new Television(450);
$controller1ForTv1 = new Controller( 0, false);
$controller2ForTv1 = new Controller( 0, false);
$boughtTv1->addExtraItem($controller1ForTv1);
$boughtTv1->addExtraItem($controller2ForTv1);

$boughtTv2 = new Television(860);
$controllerForTv2 = new Controller( 0, false);
$boughtTv2->addExtraItem($controllerForTv2);

$boughtMicrowave = new Microwave(50);

// Include all electronics in the items object
$electronicItems = new electronicItems(
	array(
		$boughtConsole,
		$boughtTv1,
		$boughtTv2,
		$boughtMicrowave
	)
);

$electronicItemsListSorted = $electronicItems->getSortedItems("price");
?>
<h2>Electronic Items</h2>
<?php
	$i = 1; 
	foreach ($electronicItemsListSorted as $currentItemObj): 
?>
	<h3><?php echo $i . ". " . $currentItemObj->getType() ?>( $<?= $currentItemObj->getPrice() ?> )</h3>
	<div style="border: 1 dashed black">
		<?php 
			$extraItemsCurrObject = $currentItemObj->getExtraItems();
			if( sizeof($extraItemsCurrObject) > 0 ):
		?>
		<div>
			<h5>Extra Items:</h5>
			<ul>
				<?php 
					foreach ($extraItemsCurrObject as $extraItemObj):
				?>
					<li><?= $extraItemObj->getType() ?> => <?= $extraItemObj->getWired() ? "Wired" : "Remote" ?></li>		
				<?php endforeach;?>
			</ul>
		</div>
		<br/>
		<?php endif; ?>
	</div>

<?php 
	$i++;
	endforeach;
?>

<?php
	echo "<b>Q2:</b><br>";
	echo "The console cost: " . $boughtConsole->getPrice();
?>