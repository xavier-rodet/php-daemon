# php-daemon
PHP Daemon which can intercept signals to stop running properly.

## Usage

### Implementation

Example of Daemon implementation :
```
class FoobarDaemon extends Snailweb\Daemon\AbstractDaemon {

    protected $dao;

    protected function process() {
        
        $this->dao->execute("INSERT INTO table (time) VALUES (%d)", time());
    }
    
    protected function setUp()
    {
        $this->dao->connect();
    }
    
    protected function tearDown()
    {
        $this->dao->disconnect();
    }
}
```


### Instanciation
```
$foobar = new FoobarDaemon();
$foobar->run();
```


## Recommandations
PHP is not the safer way to run process because of memory leaks, to avoid it this Daemon will automatically restart depending of lifetime and memory usage (set by default options).

That means you MUST use a service like [Supervisor](http://supervisord.org/) to run this daemon truly forever !

