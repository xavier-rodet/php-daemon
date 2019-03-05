# php-daemon
PHP Daemon which can intercept signals to stop running properly.

## Usage

Create a Processor for your task
```
```


Example of Daemon usage :
```
```

By default daemon's strategy is to run forever, but you can easily change his behaviour.


Run daemon for 5 process iterations
```
$daemon->run(new Snailweb\Daemon\Strategy\Iteration(5));
```

Run daemon for 1 minute
```
$daemon->run(new Snailweb\Daemon\Strategy\Timer(60));
```

## Recommandations
PHP is not the safer way to run process because of memory leaks, to avoid it this Daemon will automatically restart depending of his lifetime and memory usage.

That means you MUST use a service like [Supervisor](http://supervisord.org/) to run this daemon truly forever !

