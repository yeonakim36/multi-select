# multi-select
multiselect and search function

![image](https://github.com/user-attachments/assets/9b2098d7-b549-4b34-a012-1451cd010d34)

- select all을 통해 전체 선택 가능
- search 가능
- 아래 스크립트 옵션 본인이 필요한거에 맞춰 커스텀 가능


  $('#chkveg').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
		filterBehavior: 'text', //text값으로 검색
		//filterBehavior: 'value' -> value값으로 검색

		filterFunction: function(element, query) {
			var value = $(element).text().toLowerCase();
			query = query.toLowerCase();
			return value.indexOf(query) >= 0;
		}
	});

- 참고 : https://davidstutz.github.io/bootstrap-multiselect/
