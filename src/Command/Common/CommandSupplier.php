<?php


namespace PHPMetarTafParser\Command\Common;


/**
 * Class CommandSupplier
 * @package PHPMetarTafParser\Command
 * @author Jean-Kevin KPADEY
 * Supplier of command.
 */
final class CommandSupplier
{
    /**
     * @var Command[]
     * Array of commands.
     */
    private $commands;

    /**
     * Returns the command able to parse the $code
     * @param string $code The message to parse
     * @return Command The command able to parse
     */
    public function get(string $code): ?Command
    {
        foreach ($this->commands as $command){
            if($command->canParse($code)){
                return $command;
            }
        }
        return null;
    }

    /**
     * CommandSupplier constructor.
     * Order of commands in array is important
     */
    public function __construct()
    {
        $this->commands = array(
            new WindShearCommand(),
            new WindCommand(),
            new WindVariationCommand(),
            new MainVisibilityCommand(),
            new MainVisibilityNauticalMilesCommand(),
            new MinimalVisibilityCommand(),
            new VerticalVisibilityCommand(),
            new CloudCommand()
        );
    }
}