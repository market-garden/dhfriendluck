<?php
class VoteCommModel extends BaseModel
{
	var $tableName = "vote_comment";

	protected $fields	=   array(
        'id','vote_id','user_id','comment','cTime',
		'_autoInc'	=>	true,
		'_pk'		=>	'id',
	);
    //字段类型
	protected $type	=	array(
		'id'			=>	'int(11)' ,
        'vote_id'	=>	'int(11)' ,
        'user_id'	=>	'int(11)' ,
		'comment'    =>  'text' ,
		'cTime'		=>	'int(11)' ,
	);

} 
?>