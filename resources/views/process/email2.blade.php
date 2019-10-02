<p> &nbsp; &nbsp; Xin chào bạn <strong>{{$name}}</strong>!</p></br>

<p>  &nbsp; &nbsp; Đây là danh sách văn bản mới nhất theo chủ đề của bạn với từ khóa là: 
@foreach ($keyword as $key => $values)
        <strong style="overflow: hidden; font-size: 17px; font-family: sans-serif; color: #FF0000;"><span>{{$values->keyword}}</span></strong>
       @if ($key < (count($keyword)-1)) <label style="margin-left: -5px; "><b>; </b></label>
       @else <label style="margin-left: -3px; "><b>.</b></label>
       @endif
 @endforeach
</p> </br> </br>
<table border="1">

<tr> <th align="center"> Stt </th> <th>Văn bản mới</th><th>Ngày cập nhật</th> </tr>
<?php $i = 1; ?>
	@foreach ($noidung as $value)
	
		
		<tr>
		<td> {{$i}}</td>
				
		<td>
			<a style="text-decoration: none;" href="{!!$value->link!!}"> {!!$value->title!!}</a>
		</td>
		<td>
			<span>
				<!-- {!!$value->created_at!!} -->
			<?php
				$date = substr($value->created_at,0,10);
				$day = substr($date, 8, 2);
				$month = substr($date, 5, 2);
				$year = substr($date, 0, 4);
				echo $day.'-'.$month.'-'.$year;
			?>
			</span>
		</td>
		
		
		</tr>
		<?php $i++;?>

	
	@endforeach
	
</table>