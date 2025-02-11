jQuery.profile = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $("#btn-tambah-misi").click(function () {
            $.confirm({
                title: "Masukkan Misi",
                type: "purple",
                columnClass: 'medium',
                backgroundDismissAnimation: 'glow',
                content: "<div class='form-group'>" +
                    "<label>Masukkan Misi</label>" +
                    "<textarea class='form-control misi' placeholder='Masukkan Misi ITSM'></textarea>" +
                    "</div>" +
                    "<div class='form-group'>" +
                    "<label>Nomor Urut Misi</label>" +
                    "<input type='number' class='form-control urutan' placeholder='Masukkan Nomor Urut Misi' min='1'>" +
                    "</div>",
                buttons: {
                    confirm: {
                        text: 'Simpan Misi',
                        btnClass: 'btn-success',
                        action: function () {
                            var misi = this.$content.find('.misi').val();
                            var nomor = this.$content.find('.urutan').val();
                            if (misi === "" || nomor === "") {
                                $.alert({
                                    title: "Warning",
                                    type: 'red',
                                    content: 'Pastikan Misi dan Nomor sudah terisi'
                                });
                                return false;
                            }
                            $("#id_misi").val(0);
                            $("#nomor").val(nomor);
                            $("#misi").val(misi);
                            $("#form-misi").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-danger'
                    }
                }
            });
        });

        $("#table").on('click', 'button.btn-edit', function () {
            var id = $(this).data('id');
            var nomor = $(this).data('nomor');
            var misi = $(this).data('misi');
            $.confirm({
                title: "Edit Misi",
                type: "purple",
                columnClass: 'medium',
                backgroundDismissAnimation: 'glow',
                content: "<div class='form-group'>" +
                    "<label>Masukkan Misi</label>" +
                    "<textarea class='form-control misi' placeholder='Masukkan Misi ITSM'>" + misi + "</textarea>" +
                    "</div>" +
                    "<div class='form-group'>" +
                    "<label>Nomor Urut Misi</label>" +
                    "<input type='number' class='form-control urutan' placeholder='Masukkan Nomor Urut Misi' value='" + nomor + "' min='1'>" +
                    "</div>",
                buttons: {
                    confirm: {
                        text: 'Simpan Misi',
                        btnClass: 'btn-success',
                        action: function () {
                            var misi = this.$content.find('.misi').val();
                            var nomor = this.$content.find('.urutan').val();
                            if (misi === "" || nomor === "") {
                                $.alert({
                                    title: "Warning",
                                    type: 'red',
                                    content: 'Pastikan Misi dan Nomor sudah terisi'
                                });
                                return false;
                            }
                            $("#id_misi").val(id);
                            $("#nomor").val(nomor);
                            $("#misi").val(misi);
                            $("#form-misi").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-danger'
                    }
                }
            })
        });

        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            $.confirm({
                title: "Konfirmasi!",
                type: "red",
                columnClass: 'medium',
                backgroundDismissAnimation: 'glow',
                content: "Apakah anda yakin akan mengahapus misi ini ?",
                buttons: {
                    confirm: {
                        text: 'Hapus',
                        btnClass: 'btn-danger',
                        action: function () {
                            $("#id_misi_del").val(id);
                            $("#form-misi-del").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-purple'
                    }
                }
            })
        });

        $("#btn-simpan-visi").click(function () {
            if ($("#visi_").val()) {
                $.confirm({
                    title: "Konfirmasi!",
                    type: "red",
                    columnClass: 'medium',
                    backgroundDismissAnimation: 'glow',
                    content: "Apakah anda yakin akan mengubah visi institusi ?",
                    buttons: {
                        confirm: {
                            text: 'Simpan',
                            btnClass: 'btn-success',
                            action: function () {
                                $("#visi").val($("#visi_").val());
                                $("#form-visi").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-danger'
                        }
                    }
                });
            } else
                $.alert({
                    title: "Warning",
                    type: 'red',
                    content: 'Pastikan Visi sudah terisi'
                });
        });

        $("#btn-simpan-so").click(function () {
            if ($("#file_struktur").val()) {
                $.confirm({
                    title: "Konfirmasi!",
                    type: "red",
                    columnClass: 'medium',
                    backgroundDismissAnimation: 'glow',
                    content: "Apakah anda yakin akan mengubah struktur organisasi ?",
                    buttons: {
                        confirm: {
                            text: 'Simpan',
                            btnClass: 'btn-success',
                            action: function () {
                                $("#so_form").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-danger'
                        }
                    }
                });
            } else
                $.alert({
                    title: "Warning",
                    type: 'red',
                    content: 'Pastikan File Struktur Organisasi sudah terisi'
                });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.profile.init();
});
