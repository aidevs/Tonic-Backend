<div class="mainLinks loginFrom register">
     <?php echo $this->Form->create('User', array('inputDefaults' =>array('autocomplete'=>'off'))); 
          echo $this->Form->input('id'); ?>
        <div class="inputDiv notesBlock">
            <h4>Notes</h4>
           <?php  echo $this->Form->input('notes',array('rows'=>10,'label'=>false)); ?>
        </div>
       <div class="inputDiv marginTp20">           
           <div data-toggle="buttons" class="btn-group btn-block">
               <label class="btn <?php echo ($this->request->data['User']['show_notes']==1)?'btn-danger':'btn-success'; ?> btn-block">
                   <input <?php echo ($this->request->data['User']['show_notes']==1)?'checked="checked"':''; ?> type="checkbox" value="1" name="data[User][show_notes]" class="toggle"><span><?php echo ($this->request->data['User']['show_notes']==1)?'Hide To Barber':'Show To Barber'; ?></span></label>
           </div>
        </div>
       <div class="inputDiv">
           <input type="submit" value="Save">
       </div>
     <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->Common->loadJsClass('Notes'); ?>