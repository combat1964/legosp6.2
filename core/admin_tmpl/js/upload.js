/**
 * Created by psergey on 07.08.14.
 */
window.onload = function(){

    //Check File API support
    if(window.File && window.FileList && window.FileReader)
    {
        var filesInput = document.getElementById("files");

        filesInput.addEventListener("change", function(event){

            var files = event.target.files; //FileList object
            var output = $("#result_upload");

            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];

                //Only pics
                if(!file.type.match('image'))
                {
                    alert(file.name+' - Недопустимы тип файла');
                    continue;
                }

                var picReader = new FileReader();

                picReader.addEventListener("load",function(event){

                    var picFile = event.target;

                    var div = document.createElement("div");

                    div.innerHTML = "<span class='thumbnail'>" +
                        "<img src='" + picFile.result + "' title='" + picFile.name + "'/>" +
                        "</span>" +
                        "<input type='text' placeholder='Описание' name='thumb_desc[]'>";

                    output.append(div);

                });

                //Read the image
                picReader.readAsDataURL(file);
            }

        });
    }
    else
    {
        console.log("Your browser does not support File API");
    }
}