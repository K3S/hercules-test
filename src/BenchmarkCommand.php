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

    /**
     * @var \DateTimeInterface
     */
    private $startTime;

    /**
     * @var string
     */
    private $duration;


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

        $this->addArgument(
            'duration',
            InputArgument::OPTIONAL,
            'For how many seconds would you like the test to run?',
            '60'
        );

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

        $this->duration = $input->getArgument('duration');

        // Record start time
        $this->startTime = new \DateTimeImmutable();
        $this->output->writeln('Start time: ' . $this->startTime->format('m/d/Y H:i:s'));
        $this->output->writeLn('The test will run for ' . $this->duration . ' seconds');

        $this->insertData();
        $this->output->writeLn('Completed ' . $this->numberOfProgramCalls . ' program calls...');

        // Calculate average duration (seconds per program call)
        $averageDuration = bcdiv($this->duration, (string)$this->numberOfProgramCalls, 5);
        $this->output->writeLn("Average duration was $averageDuration seconds");
        $this->output->writeln("Number of program calls: " . $this->numberOfProgramCalls);

        return self::SUCCESS;
    }

    /**
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     * @return string
     */
    private function getInterval(\DateTimeInterface $from, \DateTimeInterface $to): string
    {
        return number_format(
            strtotime($to->format('Y-m-d H:i:s')) - strtotime($from->format('Y-m-d H:i:s'))
        );
    }

    private function insertData()
    {
        $this->cleanUp();

        // Get test data to insert
        $lifters = $this->getLifters();
        $liftWeight = 123;

        // Call program for specified duration
        $lifterIndex = 0;
        for ($difference = 0; $difference <= $this->duration; $difference = $this->getInterval($this->startTime, new \DateTimeImmutable())) {

            $this->toolkit->pgmCall('HERC_C', 'HERC', [
                $this->toolkit->AddParameterChar('both', 25, 'Lifter', 'LIFTER', $lifters[$lifterIndex]),
                $this->toolkit->AddParameterPackDec('both', 3, 0, 'Lift Weight', 'LIFT_WGT', $liftWeight),
                $this->toolkit->AddParameterChar('both', 19, 'Lift Time', 'LIFT_TIME', (new \DateTime('now'))->format('Y-m-d H:i:s'))
            ]);

            $this->numberOfProgramCalls++;
            $lifterIndex++;
            if ($lifterIndex > count($lifters)) {
                $lifterIndex = 0;
            }
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