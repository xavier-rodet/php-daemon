# php-daemon
PHP Daemon which can intercept signals to stop running properly.

## Installation

```composer require snailweb/php-daemon```

## Recommendations
PHP is not the safer way to run process because of memory leaks, to avoid it this Daemon will automatically stop depending on his lifetime and memory usage.

That means you MUST use a service like [Supervisor](http://supervisord.org/) to run this daemon truly forever !


## Usage


### Create your processor
Create a Processor to accomplish your task
```php
final class AdminNotifierProcessor implements \Snailweb\Daemon\Processor\ProcessorInterface
{
    private $dao;
    private $notifier;

    // Inject your dependencies
    public function __construct(DAO $dao, Notifier $notifier)
    {
        $this->dao = $dao;
        $this->notifier = $notifier;
    }

    // Initialize stuff
    public function setUp(): void
    {
        $this->dao->connect();
        $this->notifier->setEmail('admin@domain.com');
    }

    // CLear stuff
    public function tearDown(): void
    {
        $this->dao->disconnect();
        unset($this->dao, $this->notifier);
    }

    // Do your stuff
    public function process(): void
    {
        $results = $this->dao->query("SELECT id, message FROM notifications WHERE status='notify'");
        foreach ($results as $result) {
            $this->notifier->notify($result->message);
            $this->dao->query("UPDATE table SET status = 'notified' WHERE id = ?", $results->id);
        }
    }
}
```

### Basic Daemon usage :
```php
$processor = new AdminNotifierProcessor($dao, $notifier);

$daemon = new \Snailweb\Daemon\Daemon($processor);
$daemon->run();
```

By default daemon's strategy is to run forever however you can change his behaviour upon your needs.

### Other's Daemon usage :

Run daemon for 5 process iterations
```php
$strategy = new Snailweb\Daemon\Strategy\Iteration(5);
$daemon->run($strategy);
```

Run daemon for 1 minute
```php
$strategy = new Snailweb\Daemon\Strategy\Timer(60);
$daemon->run($strategy);
```

You can easily create your own Strategy by extending `\Snailweb\Daemon\Strategy\AbstractStrategy`

### Configure the daemon

The default daemon configuration can be override
```php
$daemon->setOptions([
    'run_ttl' => 86400, // the daemon will stop after 1 day of runtime
    'run_memory_limit' => 128, // the daemon will stop when he reached 128MB of memory usage
    'process_min_exec_time' => 100, // The minimum time between 2 process execution (to avoid CPU overload when your process has nothing do)
]);
```
Note: you don't have to set all options, just the ones you want to override :)

### Handle signals

This daemon allow you to handle [UNIX signals](https://en.wikipedia.org/wiki/Signal_(IPC)#POSIX_signals) to change his behaviour.

Create your SignalHandler
```php
final class MySignalHandler extends \Snailweb\Daemon\Signals\Handler\AbstractSignalsHandler
{
    public function handle(int $signal, Snailweb\Daemon\Daemon $daemon): void
    {
        switch ($signal) {
            case SIGINT:

                // Change daemon's behaviour

                // Apply an other processor
                $daemon->setProcessor(new ProperExitProcessor());
                // For only 10 more iterations
                $daemon->setStrategy(new \Snailweb\Daemon\Strategy\Iteration(10));

                break;
            case SIGTERM:

                // Instantly stop our script
                $daemon->stop();

                break;
        }
    }
}
```

Apply the signal handler to your daemon :
```php
$processor = new AdminNotifierProcessor();

$signals = new \Snailweb\Daemon\Signals\Signals([SIGINT, SIGTERM]);
$signalsListener = new \Snailweb\Daemon\Signals\Listener\SignalsListener();
$signalsHandler = new MySignalHandler();
$signalsManager = new \Snailweb\Daemon\Signals\Manager\SignalsManager($signals, $signalsListener, $signalsHandler);

$daemon = new \Snailweb\Daemon\Daemon($processor, $signalsManager);
$daemon->run();
```


