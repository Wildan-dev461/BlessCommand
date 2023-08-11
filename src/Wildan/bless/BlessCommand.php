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
        $this->getLogger()->info("Bless Plugin enabled.");
    }

    public function onDisable(): void {
        $this->getLogger()->info("Bless Plugin disabled.");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "bless") {
            if (!$sender instanceof Player) {
                $sender->sendMessage("This command can only be used in-game.");
                return true;
            }

            $player = $sender;
            if ($this->removeNegativeEffects($player)) {
                $player->sendMessage("You have been blessed! Negative effects removed.");
            } else {
                $player->sendMessage("There is no bad effect to remove in your body.");
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

