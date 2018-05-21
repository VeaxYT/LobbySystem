<?php
namespace iTzFreeHD\LB;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as c;

class Listeners implements Listener {

    private $plugin;

    public function __construct(LobbySystem $plugin) {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        @mkdir($this->plugin->getDataFolder().'/player');
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        $JoinMenu = $cfg->get('JoinMenu');

        $pcfg = new Config($this->plugin->getDataFolder().'/player/'.$event->getPlayer()->getName().'.yml',Config::YAML);
        $pcfg->set("Menu", $JoinMenu);
        $pcfg->save();

        $this->setItems($event->getPlayer(), $JoinMenu);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $in = $event->getPlayer()->getInventory()->getItemInHand()->getCustomName();
        $inv = $player->getInventory();

        $cfg = new Config($this->plugin->getDataFolder()."/Items.yml", Config::YAML);
        $pcfg = new Config($this->plugin->getDataFolder().'/player/'.$event->getPlayer()->getName().'.yml',Config::YAML);
        $menu = $pcfg->get('Menu');
        $fdata = $cfg->get($menu);

        //Exit [Back]
        if($in == c::RESET . c::RED . "Exit") {
            $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
            $JoinMenu = $cfg->get('JoinMenu');
            $this->setItems($player, $JoinMenu);
        }
        foreach ($fdata as $gdata) {
            if ($gdata['name'] == $in) {
                $sdata = $cfg->get($menu);
                $data = $sdata[c::clean($in)];

                if ($in == $data['name']) {
                    $ac = explode(':', $data['action']);
                    if ($data['permissions'] === "") {

                        if ($ac[0] === 'msg') {
                            $player->sendMessage($ac[1]);
                        } elseif ($ac[0] === 'cmd'){
                            $this->plugin->getServer()->dispatchCommand($event->getPlayer(), $ac[1]);
                        } elseif ($ac[0] === 'menu') {
                            $this->setItems($player, $ac[1]);
                        } elseif ($ac[0] === 'tp') {
                            $coords = explode('.', $ac[0]);
                            if ($ac[1] == 'spawn') {

                                $x = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
                                $y = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getY();
                                $z = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
                                $event->getPlayer()->teleport(new Vector3($x, $y, $z), 0, 0);
                            } else {
                                $event->getPlayer()->teleport(new Position($coords[0], $coords[1], $coords[2], $coords[3]), 0, 0);
                            }
                        }


                    } else {
                        if ($player->hasPermission($data['permissions'])) {
                            if ($ac[0] === 'msg') {
                                $player->sendMessage($ac[1]);
                            } elseif ($ac[0] === 'cmd'){
                                $this->plugin->getServer()->dispatchCommand($event->getPlayer(), $ac[1]);
                            } elseif ($ac[0] === 'menu') {
                                $this->setItems($player, $ac[1]);
                            } elseif ($ac[0] === 'tp') {
                                $coords = explode('.', $ac[0]);
                                if ($ac[1] == 'spawn') {

                                    $x = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
                                    $y = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getY();
                                    $z = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
                                    $event->getPlayer()->teleport(new Vector3($x, $y, $z), 0, 0);
                                } else {
                                    $event->getPlayer()->teleport(new Position($coords[0], $coords[1], $coords[2], $coords[3]), 0, 0);
                                }

                            }
                        } else {
                            $player->sendMessage($cfg->get('NoPermission'));
                        }
                    }
                }
            }
        }
    }

    //no Viod
    public function onMove(PlayerMoveEvent $event) {
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        if ($cfg->get('noVoid') === true) {
            $py = $event->getPlayer()->getY();

            if ($py < 5) {
                $event->getPlayer()->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(), 0, 0);
            }
        }


    }


    //Schaden
    public function onDamage(EntityDamageEvent $event)
    {
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        if ($cfg->get('noDamage') === true) {
            $event->setCancelled(true);
        }

    }

    //noDrop
    public function onDrop(PlayerDropItemEvent $event)
    {
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        if ($cfg->get('noDrop') === true) {
            $event->setCancelled();
        }

    }

    //noHunger
    public function onHunger(PlayerExhaustEvent $event)
    {
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        if ($cfg->get('noHunger') === true) {
            $event->setCancelled(true);
        }

    }

    //Build
    public function onPlace(BlockPlaceEvent $event)
    {
        $name = $event->getPlayer()->getName();
        if (!in_array($name, $this->plugin->buildmode)) {

            $event->setCancelled();

        }
    }


    public function onBreak(BlockBreakEvent $event)
    {
        $name = $event->getPlayer()->getName();
        if (!in_array($name, $this->plugin->buildmode)) {

            $event->setCancelled();

        }
    }

    public function noInvMove(InventoryTransactionEvent $event)
    {
        $cfg = new Config($this->plugin->getDataFolder() . '/Items.yml', Config::YAML);
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $build = $cfg->get('Build-Perms');
            if ($player->hasPermission($build)) {

            }else {
                $event->setCancelled();
            }
        }
    }

    //SetItem
    public function setItems(Player $player, $menu)
    {
        $inv = $player->getInventory();
        $inv->clearAll();

        $cfg = new Config($this->plugin->getDataFolder()."/Items.yml", Config::YAML);
        $sdata = $cfg->get($menu);

        foreach ($sdata as $data) {
            $id = explode(':', $data['id']);

            $item = Item::get($id[0], $id[1], $id[2]);
            $item->setCustomName($data['name']);

            $inv->setItem($data['slot'], $item);
        }
        $pcfg = new Config($this->plugin->getDataFolder().'/player/'.$player->getName().'.yml',Config::YAML);
        $pcfg->set("Menu", $menu);
        $pcfg->save();

    }
}