<?php
declare(strict_types=1);

namespace Console;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
     * @var int
     */
    private $numberOfProgramCalls = 0;


    public function configure()
    {
        $this->setName('app:benchmark')
            ->setDescription('Runs a benchmark test to measure PHP API call performance')
            ->setHelp('This command allows you to measure the performance of an API call from PHP');

        $this->addArgument(
            'username',
        InputArgument::REQUIRED,
        'What is the database user profile?');

        $this->addArgument(
            'password',
            InputArgument::REQUIRED,
            'What is the database password?');

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

        // Build database adapter and toolkit objects
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $adapterConfig = $this->getAdapterConfig($username, $password);
        $this->adapter = new Adapter($adapterConfig);
        $this->toolkit = $this->getToolkitObject();

        // Record start time
        $startTime = microtime(true);
        $this->output->writeln('Start time: ' . $startTime);

        for ($i = 0; $i <= 15; $i++) {
            $this->insertData();
            $this->output->writeLn('Completed ' . $this->numberOfProgramCalls . ' program calls...');
        }

        // Record end time
        $endTime = microtime(true);
        $this->output->writeln('End time: ' . $endTime);

        // Calculate total duration in seconds
        $duration = bcsub((string)$endTime, (string)$startTime, 25);
        $this->output->writeln("Duration was $duration seconds");

        // Calculate average duration (seconds per program call)
        $averageDuration = bcdiv($duration, (string)$this->numberOfProgramCalls, 25);
        $this->output->writeLn("Average duration was $averageDuration seconds");
        $this->output->writeln("Number of program calls: " . $this->numberOfProgramCalls);

        return self::SUCCESS;
    }

    private function insertData()
    {
        $this->cleanUp();

        // Get test data to insert
        $lifters = $this->getLifters();
        $liftWeight = 80;

        // Call program once per lifter/row
        foreach ($lifters as $lifter) {
            $this->toolkit->pgmCall('HERC_C', 'HERC', [
                $this->toolkit->AddParameterChar('both', 25, 'Lifter', 'LIFTER', $lifter),
                $this->toolkit->AddParameterPackDec('both', 3, 0, 'Lift Weight', 'LIFT_WGT', $liftWeight++),
                $this->toolkit->AddParameterChar('both', 14, 'Lift Time', 'LIFT_TIME', (new \DateTime('now'))->format('Y-m-d H:i:s'))
            ]);

            $this->numberOfProgramCalls++;
        }
    }

    /**
     * @return array|string[]
     */
    private function getLifters(): array
    {
        return require __DIR__ . '/deities.php';
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

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    private function getAdapterConfig(string $username, string $password): array
    {
        return array_merge(require __DIR__ . '/../config/database.php', [
            'username' => $username,
            'password' => $password,
        ]);
    }

    /**
     * @return array
     */
    private function getToolkitConfig(): array
    {
        return require __DIR__ . '/../config/toolkit.php';
    }

    /**
     * @return ToolkitInterface
     * @throws \Exception
     */
    private function getToolkitObject(): ToolkitInterface
    {
        $toolkit = new Toolkit(
            $this->adapter->getDriver()->getConnection()->getResource(),
            null,
            null,
            'pdo'
        );

        $toolkit->setOptions($this->getToolkitConfig());

        return $toolkit;
    }
}