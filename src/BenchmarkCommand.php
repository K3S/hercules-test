<?php
declare(strict_types=1);

namespace Console;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ToolkitApi\Toolkit;
use ToolkitApi\ToolkitInterface;

final class BenchmarkCommand extends Command
{
    /**
     * @var Adapter|AdapterInterface
     */
    private $adapter;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Toolkit|ToolkitInterface
     */
    private $toolkit;

    /**
     * BenchmarkCommand constructor.
     * @param Adapter|AdapterInterface $adapter
     * @param Toolkit|ToolkitInterface $toolkit
     */
    public function __construct(AdapterInterface $adapter, ToolkitInterface $toolkit)
    {
        $this->adapter = $adapter;
        $this->toolkit = $toolkit;

        parent::__construct();
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class),
            $container->get(Toolkit::class)
        );
    }

    public function configure()
    {
        $this->setName('app:benchmark')
            ->setDescription('Runs a benchmark test to measure PHP API call performance')
            ->setHelp('This command allows you to measure the performance of an API call from PHP');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->cleanUp();

        // Get test data to insert
        $lifters = $this->getLifters();
        $liftWeight = 80;

        // Record start time
        $startTime = microtime(true);
        $this->output->writeln('Start time: ' . $startTime);

        // Call program once per lifter/row
        $numberOfProgramCalls = 0;
        foreach ($lifters as $lifter) {
            $this->toolkit->pgmCall('HERC_C', 'HERC', [
                $this->toolkit->AddParameterChar('both', 25, 'Lifter', 'LIFTER', $lifter),
                $this->toolkit->AddParameterPackDec('both', 3, 0, 'Lift Weight', 'LIFT_WGT', $liftWeight++),
                $this->toolkit->AddParameterChar('both', 14, 'Lift Time', 'LIFT_TIME', (new \DateTime('now'))->format('Y-m-d H:i:s'))
            ]);
            $numberOfProgramCalls++;
        }

        // Record end time
        $endTime = microtime(true);
        $this->output->writeln('End time: ' . $endTime);

        // Calculate total duration in seconds
        $duration = bcsub((string)$endTime, (string)$startTime, 25);
        $this->output->writeln("Duration was $duration seconds");

        // Calculate average duration (seconds per program call)
        $averageDuration = bcdiv($duration, (string)$numberOfProgramCalls, 25);
        $this->output->writeLn("Average duration was $averageDuration seconds");

        return self::SUCCESS;
    }

    /**
     * @return array|string[]
     */
    private function getLifters(): array
    {
        return [
            'JUPITER',
            'MARS',
            'QUIRINUS',
            'CARMENTIS',
            'CERES',
            'FALACER',
            'FLORA',
            'FURRINA',
            'PALATUA',
            'POMONA',
            'PORTUNUS',
            'VULCAN',
            'VOLTURNUS',
            'JANUS',
            'SATURN',
            'GENIUS',
            'MERCURY',
            'APOLLO',
            'NEPTUNE',
            'SOL',
            'ORCUS',
            'LIBER',
            'TELLUS',
            'JUNO',
            'LUNA',
            'DIANA',
            'MINERVA',
            'VENUS',
            'VESTA',
            'BACCHUS',
            'DIANA',
            'DECIMA',
            'HORA',
            'INTERCIDONA',
            'LUA',
            'MITHRAS',
            'MENA',
            'NEPTUNE',
            'NECESSITAS',
            'ORCUS',
            'PLUTO',
            'PALATUA',
            'ROMA',
            'TERMINUS',
            'TIBERINUS',
            'TRIVIA',
            'VICTORIA',
            'VIRTUS',
            'TELLUS',
            'TEMPESTAS',
        ];
    }

    /**
     * @param string $sql
     * @return bool
     */
    private function runSQL(string $sql): bool
    {
        /** @var Result $result */
        $result = $this->adapter->query($sql)->execute();

        return $result->valid();
    }

    private function cleanUp()
    {
        $this->runSQL("DELETE FROM HERC.LIFTING");
    }
}