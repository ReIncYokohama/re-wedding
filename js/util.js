
/*ObjectArrayをsortする。
	※受け取ったArrayに操作をしない。どこかのオブジェクトと紐づいているかもしれないから。
	setObject
		type		0	数字
					1	文字
		desc		true or false
		key		並び替えに使用するkey
*/
var SortObjectArray = function(objectArray,setObject){
	var type = (setObject.hasOwnProperty('type') && setObject['type'])?setObject['type']:0;
	var desc = (setObject.hasOwnProperty('desc') && setObject['desc'])?setObject['desc']:false;
	var key = (setObject.hasOwnProperty('key') && setObject['key'])?setObject['key']:false;
	if(!key) return objectArray;
	
	var objectArrayLength = objectArray.length;
	var sortObjectArray = new Array();
	for(var i=0;i<objectArrayLength;++i){
		sortObjectArray.push(objectArray[i]);
	}
	
	var returnObjectArray = new Array();
	switch(type){
		case 0:
			if(desc){
				for(var i=0;i<objectArrayLength;++i){
					var sortObjectArrayLength = sortObjectArray.length;
					var index = 0;
					var maxValue = sortObjectArray[0][key];
					for(var j=1;j<sortObjectArrayLength;++j){
						if(maxValue < sortObjectArray[j][key]){
							maxValue = sortObjectArray[j][key];
							index = j;
						}
					}
					returnObjectArray.push(sortObjectArray[index]);
					sortObjectArray.splice(index,1);
				}
			}else{
				for(var i=0;i<objectArrayLength;++i){
					var sortObjectArrayLength = sortObjectArray.length;
					var index = 0;
					var minValue = sortObjectArray[0][key];
					for(var j=1;j<sortObjectArrayLength;++j){
						if(minValue > sortObjectArray[j][key]){
							minValue = sortObjectArray[j][key];
							index = j;
						}
					}
					returnObjectArray.push(sortObjectArray[index]);
					sortObjectArray.splice(index,1);
				}
			}
			break;
		case 1:
			if(desc){
				for(var i=0;i<objectArrayLength;++i){
					var sortObjectArrayLength = sortObjectArray.length;
					var index = 0;
					var maxValue = sortObjectArray[0][key];
					for(var j=1;j<sortObjectArrayLength;++j){
						//二文字目以降も考慮
						
						var charLength = (String(maxValue).length>String(sortObjectArray[j][key]).length)?String(sortObjectArray[j][key]).length:String(maxValue).length;
						for(var k=0;k<charLength;++k){
							if(String(maxValue).charCodeAt(k) > String(sortObjectArray[j][key]).charCodeAt(k)){
								break;
							}
							if(String(maxValue).charCodeAt(k) < String(sortObjectArray[j][key]).charCodeAt(k)){
								maxValue = sortObjectArray[j][key];
								index = j;
								break;
							}
						}
						
						if(String(maxValue).charCodeAt(0) < String(sortObjectArray[j][key]).charCodeAt(0)){
							maxValue = sortObjectArray[j][key];
							index = j;
						}
						
					}
					returnObjectArray.push(sortObjectArray[index]);
					sortObjectArray.splice(index,1);
				}
			}else{
				for(var i=0;i<objectArrayLength;++i){
					var sortObjectArrayLength = sortObjectArray.length;
					var index = 0;
					var minValue = sortObjectArray[0][key];
					for(var j=1;j<sortObjectArrayLength;++j){
						
						var charLength = (String(minValue).length>String(sortObjectArray[j][key]).length)?String(sortObjectArray[j][key]).length:String(minValue).length;
						for(var k=0;k<charLength;++k){
							if(String(minValue).charCodeAt(k) < String(sortObjectArray[j][key]).charCodeAt(k)){
								break;
							}
							if(String(minValue).charCodeAt(k) > String(sortObjectArray[j][key]).charCodeAt(k)){
								minValue = sortObjectArray[j][key];
								index = j;
								break;
							}
						}
						
						/*
						if(String(minValue).charCodeAt(0) > String(sortObjectArray[j][key]).charCodeAt(0)){
							minValue = sortObjectArray[j][key];
							index = j;
						}*/
					}
					returnObjectArray.push(sortObjectArray[index]);
					sortObjectArray.splice(index,1);
				}
			}
			break;
	}
	return returnObjectArray;
};

