<?php
class Route
{
    public $id;
    public $path;
    public $start;
    public $node;
    public $end;
    public $table = 'routes';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function add_Route()
    {
        $query = "INSERT INTO routes
        SET
        path = :path,
        start = :start,
        end = :end,
        route_no = :route_no";
        $stmt = $this->conn->prepare($query);
        $this->path = htmlspecialchars(strip_tags($this->path));
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->end = htmlspecialchars(strip_tags($this->end));
        $this->route_no = htmlspecialchars(strip_tags($this->route_no));
        $stmt->bindParam(':path', $this->path);
        $stmt->bindParam(':start', $this->start);
        $stmt->bindParam(':end', $this->end);
        $stmt->bindParam(':route_no', $this->route_no);

        if ($stmt->execute()) {
            $response = array(
                "code" => 200,
                "message" => "Route Added.",
            );
            echo json_encode($response);
        } else {
            $response = array(
                "code" => 400,
                "message" => "Route not added.",
            );
            echo json_encode($response);
        }
    }

    public function read_RouteAll()
    {
        $query = 'SELECT * FROM routes';
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $route_array = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $route_item = array(
                    'id' => $id,
                    'path' => $path,
                    'start' => $start,
                    'end' => $end,
                    'route_no' => $route_no,
                );
                array_push($route_array, $route_item);
            }
            $response = array(
                "code" => 200,
                "message" => $route_array,
            );
            echo json_encode($response);
        } else {
            $response = array(
                "code" => 500,
                "message" => "No data",
            );
            echo json_encode($response);
        }
    }

    public function read_RouteById($id = null)
    {
        $query = "SELECT r.id, r.path, r.route_no, n1.name as start, n2.name as end
        FROM routes r
        JOIN nodes n1 on r.start = n1.id
        JOIN nodes n2 on r.end = n2.id
        WHERE r.id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $route_array = array(
                'id' => $result['id'],
                'path' => $result['path'],
                'start' => $result['start'],
                'end' => $result['end'],
                'route_no' => $result['route_no'],
            );
            $response = array(
                "code" => 200,
                "message" => $route_array,
            );
        } else {
            $response = array(
                "code" => 500,
                "message" => "No data found.",
            );
        }
        echo json_encode($response);
    }

    public function read_RouteByNo($num)
    {
        $query = "SELECT r.id, r.path, r.route_no,
        n1.name as start,
        n2.name as end
        FROM routes r
        JOIN nodes n1 on r.start = n1.id
        JOIN nodes n2 on r.end = n2.id
        WHERE route_no =  :num";
        $stmt = $this->conn->prepare($query);
        $num = htmlspecialchars(strip_tags($num));
        $stmt->bindParam(':num', $num);
        $stmt->execute();
        $result = $stmt;
        $num = $result->rowCount();
        if ($num) {
            $route_array = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $route_item = array(
                    'id' => $id,
                    'route_no' => $route_no,
                    'path' => $path,
                    'start' => $start,
                    'end' => $end,
                );
                array_push($route_array, $route_item);
            }
            $response = array(
                "code" => 200,
                "message" => $route_array,
            );
        } else {
            $response = array(
                "code" => 500,
                "message" => "No data",
            );
        }
        echo json_encode($response);
    }

    public function read_RouteByStart($start){}

    public function read_RouteByEnd($end){}

    public function read_routeToNode($node){}