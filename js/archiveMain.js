(function (OCA) {
	OCA.ArchiveViewer = OCA.ArchiveViewer || {}
	

	OCA.ArchiveViewer.Mimes = [];

    if (!OCA.ArchiveViewer.AppName) {
        OCA.ArchiveViewer = {
            AppName: "archive-viewer"
        };
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
							window.location.href = OC.generateUrl("/apps/" + OCA.ArchiveViewer.AppName + "/");
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
