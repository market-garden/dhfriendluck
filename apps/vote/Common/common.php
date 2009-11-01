    <?php
    /**
     * getIsVote 
     * 返回是否已经投票
     * @param mixed $vid 
     * @param mixed $mid 
     * @access public
     * @return void
     */
	function getIsVote($vid,$mid){
		//2009-4-2 增加空值判断
		if(!$vid || !$mid) return false;

         $voteUserDao = D("VoteUser");
         $vote_id = intval($vid);
         $count = $voteUserDao->count("vote_id='$vote_id' AND user_id='$mid'");
         if($count>0){
             return "，你已经投过票了！";
         }else{
			 return "";
		 }
	}

	