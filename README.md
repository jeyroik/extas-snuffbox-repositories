# extas-snuffbox-repositories

Repositories snuffbox for Extas.

All methods include simple snuff repos inside already. 

# using

```php
class Test
{
    use \extas\components\repositories\TSnuffRepositoryDynamic;

    protected function setUp(): void
    {
        $this->createSnuffDynamicRepositories([
            ['repository_name', 'primary key', 'extas\\item\\Class']
        ]);
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }
}
```
