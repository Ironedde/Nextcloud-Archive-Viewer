(function (OCA) {
	OCA.ArchiveViewer = OCA.ArchiveViewer || {}

	
	OCA.ArchiveViewer.Mimes = [];

    if (!OCA.ArchiveViewer.AppName) {
        OCA.ArchiveViewer = {
            AppName: "archive-viewer"
        };
    }
    OCA.ArchiveViewer.ViewFileNewWindow = function (filePath) {

        var ncClient = OC.Files.getClient();
        ncClient.getFileInfo(filePath)
        .then(function (status, fileInfo) {
            //var url = OC.generateUrl("/apps/" + OCA.ArchiveViewer.AppName + "/{fileId}", {
            //    fileId: fileInfo.id
            //});
            var url = OC.generateUrl("/apps/" + OCA.ArchiveViewer.AppName + "/{fileId}", {
                fileId: fileInfo.id
            });
            // TODO: since we cannot edit more than one diagram per window maybe we need to just set the URL
            window.location.href = url
        })
        .fail(function (status) {
            console.log("Error: " + status);
            // TODO: show notification to user
        });
    }
    OCA.ArchiveViewer.FileList = {
        attach: function (fileList) {
            if (fileList.id == "trashbin") {
                return;
            }

            $.get(OC.generateUrl("apps/" + OCA.ArchiveViewer.AppName + "/ajax/config"))
            .done(function (json) {
                //OCA.AppSettings = json.settings;
                OCA.ArchiveViewer.Mimes = json.formats;
                $.each(OCA.ArchiveViewer.Mimes, function (ext, attr) {
                    fileList.fileActions.registerAction({
                        name: "ArchiveViewerOpen",
                        displayName: t(OCA.ArchiveViewer.AppName, "Open in ArchiveViewer"),
						mime: attr.mime,
						permissions: OC.PERMISSION_READ | OC.PERMISSION_UPDATE,
						actionHandler: function (fileName, context) {
							var dir = fileList.getCurrentDirectory();
							//TODO: Add a pretty Fileviewer with: OC.joinPaths(dir, fileName), should work....
							//window.location.href = OC.generateUrl("/apps/" + OCA.ArchiveViewer.AppName + "/");

							OCA.ArchiveViewer.ViewFileNewWindow(OC.joinPaths(dir, fileName));
						}
                    });
					//TODO: Should we realy force the fileaction on people?
					fileList.fileActions.setDefault(attr.mime, "ArchiveViewerOpen");
				});
			})
            .fail(function () {
                //TODO: Maybe we should tell someone? 
            });
		}
	}

})(OCA);
OC.Plugins.register("OCA.Files.FileList", OCA.ArchiveViewer.FileList);
