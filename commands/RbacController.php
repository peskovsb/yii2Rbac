<?php
namespace app\commands;
 
use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;
use app\rbac\AuthorRule;
use app\models\User; 
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use app\models\Post;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $rule = new AuthorRule();
        $auth->add($rule);

        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        $updatePost = $auth->createPermission('UpdatePost');
        $updatePost->description = 'Update a post';
        $auth->add($updatePost);

        $updateOwnPost = $auth->createPermission('UpdateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        $user = $auth->createRole('user');
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($user, $createPost);
        $auth->addChild($user, $updateOwnPost);

        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $user);

        $auth->addChild($updateOwnPost, $updatePost);

        $this->stdout('Done!' . PHP_EOL);
    }
    
    public function actionTest()
    {
        //Yii::$app->set('request', new \yii\web\Request());
        //$auth = Yii::$app->getAuthManager();
        
        //Yii::$app->set('request', new \yii\web\Request());

        //Yii::$app->getSession()->set('user-'.$id, $attributes);  
        //Yii::$app->session->get('session');
        //
        Yii::$app->set('request', new \yii\web\Request());
        //      
        //      
        $auth = Yii::$app->getAuthManager(); 

        $user = new User(['id'=>1,'username'=>'User']);
        $admin = new User(['id'=>2,'username'=>'Admin']);
        
        $auth->revokeAll($user->id);
        $auth->revokeAll($admin->id);

        //echo 'Roles for User : ' . PHP_EOL;
        //print_r($auth->getRolesByUser($user->id));

       $auth->assign($auth->getRole('user'), $user->id);
       $auth->assign($auth->getRole('admin'), $admin->id);

        /* $auth->assign($auth->getRole('admin'), $admin->id);*/

        //echo 'New Roles for User' . PHP_EOL;
        //print_r(implode(', ',ArrayHelper::map($auth->getRolesByUser($user->id), 'name', 'name')));

        print_r($auth->getRolesByUser($user->id));
        print_r($auth->getRolesByUser($admin->id));

        echo PHP_EOL;

        echo "####################################";

        echo PHP_EOL;

        $this->stdout("Check access for {$user->username}\n\n", Console::FG_YELLOW);
        Yii::$app->user->login($user);

        $post = new Post([
            'title' => 'Example post',
            'user_id' => $user->id
            ]); 

        var_dump(Yii::$app->user->can('createPost'));
        var_dump(Yii::$app->user->can('UpdatePost', ['post'=>$post]));
        var_dump(Yii::$app->user->can('UpdateOwnPost', ['post'=>$post]));
        echo PHP_EOL;

         echo "####################################";


        $this->stdout("Check access for {$admin->username}\n\n", Console::FG_YELLOW);
        Yii::$app->user->login($admin);

        $post = new Post([
            'title' => 'Example post',
            'user_id' => $user->id
            ]); 

        var_dump(Yii::$app->user->can('createPost'));
        var_dump(Yii::$app->user->can('UpdatePost', ['post'=>$post]));
        var_dump(Yii::$app->user->can('UpdateOwnPost', ['post'=>$post]));
        echo PHP_EOL;


        /*echo '--- Rules ----';
        echo PHP_EOL;
        Yii::$app->user->login($user);
        echo 'User Can Do This: ';
        var_dump(Yii::$app->user->can('createPost'));
         echo PHP_EOL;
         echo 'Admin Can Do This: ';
        Yii::$app->user->login($admin);
        var_dump(Yii::$app->user->can('createPost'));
        echo PHP_EOL;*/
       /* Yii::$app->user->login($user);
        if(Yii::$app->user->can('user')){
            echo 'Im user and I can DO IT';
        }else{
            echo 'FATAL ERROR!!! Access denied for this User';
        }


        echo PHP_EOL;


        var_dump(Yii::$app->authManager->checkAccess($user->id,'user'));*/

    }    

}