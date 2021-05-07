<?php 

echo $this->HTML->Form->start('register', '/home', 'post', true);

echo $this->HTML->Form->color('color', $this->color);

echo $this->HTML->Form->date('date', $this->date);
echo $this->HTML->Form->datetime_local('datetimelocal', $this->datetimelocal);

echo $this->HTML->Form->file('myfile');

echo $this->HTML->Form->month('month', $this->month);

echo $this->HTML->Form->number('mynum', $this->mynum);

echo $this->HTML->Form->range('somn', 100, 1000, $this->somn);

echo $this->HTML->Form->search('search', $this->search);

echo $this->HTML->Form->tel('tel', $this->tel);

echo $this->HTML->Form->select('mysel', $this->mysel, ['op1' => 'Option #1', 'op2' => 'Option #2']);

echo $this->HTML->Form->time('time', $this->time);
echo $this->HTML->Form->url('url', $this->url);
echo $this->HTML->Form->week('week', $this->week);

echo $this->HTML->Form->submit('register', 'Register');
echo $this->HTML->Form->reset();

echo $this->HTML->Form->end();

echo $this->somn;