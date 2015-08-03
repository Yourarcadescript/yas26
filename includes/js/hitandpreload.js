function addHit(linkid) {
	new Image().src = siteurl + 'outlink.php?id='+ linkid;
	return true;
}
function preloadimages(arr){
    var newimages=[]
    var arr=(typeof arr!="object")? [arr] : arr 
        newimages[i]=new Image()
        newimages[i].src=arr[i]
    return true;
}