<?php
namespace iTzFreeHD\LB;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as c;
use pocketmine\utils\Config;


class LobbySystem extends PluginBase {

    public $p;
    public $cfg;
    public $buildmode = array('CONSOLE');
    public $Listeners;

    public function onEnable()
    {
        $this->deleteDirectory($this->getDataFolder()."/player/");
        $this->getLogger()->info($this->p."Wurde erfolgreich gestartet");
        @mkdir($this->getDataFolder());
        $this->initConfig();
        $this->cfg = new Config($this->getDataFolder() . '/Items.yml', Config::YAML);

        $this->Listeners = new Listeners($this);
    }


    public function initConfig()
    {


        if (!file_exists($this->getDataFolder() . '/Items.yml')) {
            $cfg = new Config($this->getDataFolder() . '/Items.yml', Config::YAML);
            //JoinItems
            $cfg->set('Build-Perms', 'lobby.build');
            $cfg->set('noHunger', true);
            $cfg->set('noDamage', true);
            $cfg->set('noDrop', true);
            $cfg->set('noVoid', true);
            $cfg->set('NoPermission', '§cDu hast keine Rechte');
            $cfg->set('JoinMenu', "Main");
            $cfg->set('Main', []);
            $cfg->set('Info', []);
            $cfg->save();
            $cfg->reload();

            $items = $cfg->get('Main');

            $items['Teleporter'] = ['name' => '§6Teleporter', 'id' => '345:0:1', 'slot' => 3, 'action' => 'menu:Info', 'permissions' => ''];
            $items['Build'] = ['name' => '§6Build', 'id' => '2:0:1', 'slot' => 5, 'action' => 'cmd:build', 'permissions' => 'lobby.build'];
            $items['Tools'] = ['name' => '§6Tools', 'id' => '396:0:1', 'slot' => 8, 'action' => 'cmd:freeparticle', 'permissions' => ''];

            $cfg->set('Main', $items);

            //InfoItem

            $iitems = $cfg->get('Info');

            $iitems['Info'] = ['name' => '§bInfo', 'id' => '340:0:1', 'slot' => 0, 'action' => 'msg: §bGehe auf github.com/Hyroxing um mehr Infos zu den Einstellungen zu erhalten', 'permissions' => 'lobby.info'];
            $iitems['Spawn'] = ['name' => '§6Spawn', 'id' => '378:0:1', 'slot' => 4, 'action' => 'tp:spawn', 'permissions' => ''];
            $iitems['Exit'] = ['name' => '§cExit', 'id' => '351:1:1', 'slot' => 8, 'action' => 'menu:Main', 'permissions' => ''];

            $cfg->set('Info', $iitems);
            $cfg->save();
            $this->reloadConfig();
            unlink($this->getDataFolder()."config.yml");
        }

    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args):bool {
        $name = $sender->getName();
        $cfg = new Config($this->getDataFolder() . '/Items.yml', Config::YAML);
        $build = $cfg->get('Build-Perms');
        if ($cmd->getName() == "build" && $sender->hasPermission($build)) {
            if (!in_array($name, $this->buildmode)) {

                $this->buildmode[] = $name;
                $sender->sendMessage(c::GREEN . "Du kannst nun bauen.");

            } else {

                unset($this->buildmode[array_search($name, $this->buildmode)]);

                $sender->sendMessage(c::RED . "Du kannst nun nicht mehr bauen.");

            }

        }

        if ($cmd->getName() == "lobby") {

            if ($sender instanceof Player) {
                $spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
                $this->getServer()->getDefaultLevel()->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
                $sender->teleport($spawn, 0, 0);

            }


        }

        if ($cmd->getName() == "lbmain") {

            if ($sender instanceof Player) {
                if ($this->Listeners instanceof Listeners) {
                    $cfg = new Config($this->getDataFolder() . '/Items.yml', Config::YAML);
                    $JoinMenu = $cfg->get('JoinMenu');

                    $pcfg = new Config($this->getDataFolder().'/player/'.$sender->getName().'.yml',Config::YAML);
                    $pcfg->set("Menu", $JoinMenu);
                    $pcfg->save();

                    $this->Listeners->setItems($sender, $JoinMenu);

                }

            }


        }
        return true;
    }

    function deleteDirectory($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        rmdir($dir."/".$object);
                    }else{
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}