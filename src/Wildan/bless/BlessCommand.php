<?php

namespace Wildan\bless;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\effect\Effect;

class BlessCommand extends PluginBase {

    public function onEnable(): void {
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "bless") {
            if (!$sender instanceof Player) {
                $sender->sendMessage($this->getConfig()->get("only-ingame-message"));
                return true;
            }

            $player = $sender;
            if ($this->removeNegativeEffects($player)) {
                $player->sendMessage($this->getConfig()->get("blessed-message"));
            } else {
                $player->sendMessage($this->getConfig()->get("no-bad-effect-message"));
            }

            return true;
        }
        return false;
    }

    private function removeNegativeEffects(Player $player): bool {
        $hasNegativeEffects = false;
        foreach ($player->getEffects()->all() as $effect) {
            if ($effect->getType()->isBad()) {
                $player->getEffects()->remove($effect->getType());
                $hasNegativeEffects = true;
            }
        }
        return $hasNegativeEffects;
    }
}
