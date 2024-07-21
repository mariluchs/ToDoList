<?php
use PHPUnit\Framework\TestCase;

class MockMysqliResult
{
    public $num_rows;
    
    public function __construct($exists)
    {
        $this->num_rows = $exists ? 1 : 0;
    }

    public function fetch_assoc()
    {
        return ['name' => 'Test Task'];
    }
}

class DeleteTaskTest extends TestCase
{
    private $mysqli;
    private $stmt;
    private $stmt_update;

    protected function setUp(): void
    {
        // Мокирование объекта mysqli
        $this->mysqli = $this->createMock(mysqli::class);

        // Мокирование объекта mysqli_stmt для SELECT, DELETE и UPDATE запросов
        $this->stmt = $this->createMock(mysqli_stmt::class);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('get_result')->willReturn(new MockMysqliResult(true));
        $this->stmt->method('close');

        $this->stmt_update = $this->createMock(mysqli_stmt::class);
        $this->stmt_update->method('execute')->willReturn(true);
        $this->stmt_update->method('close');

        // Возвращаем моки из метода prepare
        $this->mysqli->method('prepare')->willReturnMap([
            ["SELECT name FROM tasks WHERE id = ?", $this->stmt],
            ["DELETE FROM tasks WHERE id = ?", $this->stmt],
            ["UPDATE lists SET number_of_tasks = number_of_tasks - 1 WHERE id = ?", $this->stmt_update]
        ]);

        // Подмена глобальных переменных
        $_GET['id'] = '1';
        $_GET['list_id'] = '1';

        // Подмена глобальной переменной для использования в тесте
        $GLOBALS['conn'] = $this->mysqli;
    }

    public function testDeleteTask()
    {
        // Подключаем тестируемый скрипт
        include '6_delete_task.php';

        // Проверка, что не было фатальных ошибок
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['conn'], $_GET['id'], $_GET['list_id']);
    }
}
