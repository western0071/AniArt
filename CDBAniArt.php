<?
class CDBAniArt {
    public $host;
    public $user;
    public $password;
    public $table;
    public $link;
    public $right = 'right';
    public $left = 'left';
    public $id = 'id';

    public function __construct(
        $host = 'localhost:/tmp/mysql.sock',
        $user = 'mysql_user',
        $password = 'mysql_password',
        $table = 'mysql_password'
    )
    {
        $this->host = $host;
        $this->user = $host;
        $this->password = $host;
        $this->table = $table;
    }

    function connect()
    {
        $link = mysqli_connect($this->host, $this->user, $this->password, "my_db");
        if (!$link)
        {
            echo "Код ошибки: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки: " . mysqli_connect_error() . PHP_EOL;
            return false;
        }
        else
        {
            $this->link = $link;
            return true;
        }
    }

    function sendQuery($query)
    {
        if($result = mysqli_query($this->link, $query))
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                print_r($row);
            }
        }
    }

    function getChildrenAll()
    {
        if($this->connect())
        {
            $this->sendQuery("SELECT * FROM $this->table WHERE ($this->right - $this->left) = 1;");
        }
    }

    function getLevelNode()
    {
        if($this->connect())
        {
            $this->sendQuery("SELECT T2.$this->id, COUNT(*) AS level 
FROM $this->table AS T1, $this->table AS T2
WHERE T2.$this->left BETWEEN T1.$this->left AS T2
GROUP BY T2.$this->id;");
        }
    }

    function fromRootToNode($nodeId)
    {
        if($this->connect())
        {
            $this->sendQuery("SELECT $nodeId,
T1.$this->id, (T1.$this->right - T1.$this->left) AS size
FROM $this->table AS T1, $this->table AS T2
WHERE T2.$this->id = $nodeId
AND T2.$this->left BETWEEN T1.$this->left AND T1.$this->right;");
        }
    }
}