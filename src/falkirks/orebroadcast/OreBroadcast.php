<?php
namespace falkirks\orebroadcast;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;

class OreBroadcast extends PluginBase implements Listener{
    public static $oreIds = [56, 14, 15, 16, 21, 73, 74, 129];
    private $foundOres = [];
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onBlockBreak(BlockBreakEvent $event){
        if(!isset($this->foundOres[$event->getPlayer()->getName()])) $this->foundOres[$event->getPlayer()->getName()] = [];
        if(OreBroadcast::isPrecious($event->getBlock()) && !in_array($event->getBlock(), $this->foundOres[$event->getPlayer()->getName()])){
            $vein = $this->getVein($event->getBlock());
            $this->foundOres[$event->getPlayer()->getName()] = array_merge($this->foundOres[$event->getPlayer()->getName()], $vein);
            $this->getServer()->broadcastMessage($event->getPlayer()->getName() . " found " . count($vein) . " " . Block::get($event->getBlock()->getId())->getName());
            unset($this->foundOres[$event->getPlayer()->getName()][array_search($event->getBlock(), $this->foundOres[$event->getPlayer()->getName()])]);

        }
    }
    public function getVein(Block $block){
        $id = $block->getId();
        $queue = [$block];
        $processed = [];
        while(!empty($queue)){
            /** @var Block $block */
            $block = array_pop($queue);
            if($block->getId() == $id){

                $next = $block->getLevel()->getBlock($block->add(1));
                if(!in_array($next, $processed)) $queue[] = $next;

                $next = $block->getLevel()->getBlock($block->add(-1));
                if(!in_array($next, $processed)) $queue[] = $next;

                $next = $block->getLevel()->getBlock($block->add(0, 1));
                if(!in_array($next, $processed)) $queue[] = $next;

                $next = $block->getLevel()->getBlock($block->add(0, -1));
                if(!in_array($next, $processed)) $queue[] = $next;

                $next = $block->getLevel()->getBlock($block->add(0, 0, 1));
                if(!in_array($next, $processed)) $queue[] = $next;

                $next = $block->getLevel()->getBlock($block->add(0, 0, -1));
                if(!in_array($next, $processed)) $queue[] = $next;
                $processed[] = $block;
            }
        }
        return $processed;
    }
    public static function isPrecious($block){
        if($block instanceof Block) $block = $block->getId();

        return in_array($block, OreBroadcast::$oreIds);
    }
}