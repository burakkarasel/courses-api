<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


$app = AppFactory::create();

$app->get("/courses/all", function (Request $request, Response $response){
   $sql = "SELECT * FROM courses";

   try {
       $db = new DB();
       $conn = $db->connect();

       $stmt = $conn->query($sql);
       $courses = $stmt->fetchAll(PDO::FETCH_OBJ);

       $db = null;
       $response->getBody()->write(json_encode($courses));
       return $response->withHeader("content-type", "application/json")->withStatus(200);
   }catch (PDOException $e){
       $error = array(
           "message" => $e->getMessage()
       );
       $response->getBody()->write(json_encode($error));
       return $response->withStatus(500);
   }
});

$app->post("/courses/add", function (Request $request, Response $response){
   $title = $request->getParsedBody()["title"];
   $couponCode = $request->getParsedBody()["couponCode"];
   $price = $request->getParsedBody()["price"];

   $sql = "INSERT INTO courses(title, couponCode, price) values(:title, :couponCode, :price)";

   try {
       $db = new DB();
       $conn = $db->connect();

       $stmt = $conn->prepare($sql);
       $stmt->bindParam(":title", $title);
       $stmt->bindParam(":couponCode", $couponCode);
       $stmt->bindParam(":price", $price);

       $result = $stmt->execute();

       $db = null;
       if($result){
           $response->getBody()->write(json_encode(array("message" => "success")));
           return $response->withHeader("content-type", "application/json")->withStatus(200);
       }
   }catch (PDOException $e){
       $error = array(
           "message" => $e->getMessage()
       );
       $response->getBody()->write(json_encode($error));
       return $response->withStatus(500);
   }
});

$app->get("/courses/getOne/{courseId}", function (Request $request, Response $response, $args){
    $id = $args["courseId"];
    $sql = "SELECT * FROM courses WHERE id = $id";

    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if($result == null){
            $response->getBody()->write(json_encode(array("message" => "course not found")));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(404);
        }
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }catch (PDOException $e){
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(500);
    }
});