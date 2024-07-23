<?php
use PHPUnit\Framework\TestCase;

class AddTaskTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Erstellen einer Mock-Datenbankverbindung
        $this->conn = $this->getMockBuilder(mysqli::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testValidTaskNameAndListId()
    {
        $_POST['task_name'] = 'Test Task';
        $_POST['list_id'] = '1';

        $this->conn->expects($this->any())
            ->method('prepare')
            ->will($this->returnValue($this->createMock(mysqli_stmt::class)));

        ob_start();
        include '5_add_task.php';
        ob_end_clean();

        $headers = headers_list();
        $this->assertNotEmpty($headers, 'No headers found');
        if (!empty($headers)) {
            $this->assertStringContainsString('4_detail_page.php?list_id=1', $headers[0]);
        }
    }

    public function testEmptyTaskName()
    {
        $_POST['task_name'] = '';
        $_POST['list_id'] = '1';

        ob_start();
        include '5_add_task.php'; 
        ob_end_clean();

        $this->assertEquals('empty_task', $_SESSION['error'] ?? null);
    }

    public function testEmptyListId()
    {
        $_POST['task_name'] = 'Test Task';
        $_POST['list_id'] = '';

        ob_start();
        include '5_add_task.php';
        ob_end_clean();

        $this->assertEquals('empty_task', $_SESSION['error'] ?? null);
    }

    public function testInvalidTaskName()
    {
        $_POST['task_name'] = 'Invalid@Task';
        $_POST['list_id'] = '1';

        ob_start();
        include '5_add_task.php';

        $this->assertEquals('invalid_symbol', $_SESSION['error'] ?? null);
    }

    public function testTaskNameLengthExceeded()
    {
        $_POST['task_name'] = str_repeat('a', 51);
        $_POST['list_id'] = '1';

        ob_start();
        include '5_add_task.php'; 
        ob_end_clean();

        $this->assertEquals('length_exceeded', $_SESSION['error'] ?? null);
    }

    protected function tearDown(): void
    {
        $this->conn = null;
    }
}
?>
