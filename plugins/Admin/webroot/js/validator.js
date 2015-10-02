$(document).ready(function(){
    $('#form_projects').bootstrapValidator({
        fields: {
            'data[Project][project_name]': {
                selector:"#project_name",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            }
        }
    });
    
    $('#form_projects_image').bootstrapValidator({
        fields: {
            image: {
                trigger: 'change keyup',
                validators: {
                    notEmpty: {
                        message: 'Upload Image'
                    }
                }
            },
            project_id: {
                selector:"#project_id",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            }
        }
    });
    
    $('#form_cmspages').bootstrapValidator({
        fields: {
            'data[CmsPage][name]': {
                selector:"#cms_pages_name",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            }
        }
    });
    
    $('#form_page_images').bootstrapValidator({
        fields: {
            image: {
                trigger: 'change keyup',
                validators: {
                    notEmpty: {
                        message: 'Upload Image'
                    }
                }
            },
            cms_page_id: {
                selector:"#cms_page_id",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            }
        }
    });
    
    $('#form_clients').bootstrapValidator({
        fields: {
            image: {
                trigger: 'change keyup',
                validators: {
                    notEmpty: {
                        message: 'Upload Image'
                    }
                }
            },
            'data[Client][client_name]': {
                selector:"#client_name",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            },
            'data[Client][client_type]': {
                selector:"#client_type",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            },
        }
    });
    
    $('#form_advertisement').bootstrapValidator({
        fields: {
            image: {
                trigger: 'change keyup',
                validators: {
                    notEmpty: {
                        message: 'Upload Image'
                    }
                }
            },
            'data[Advertisement][title]': {
                selector:"#title",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            },
            timer: {
                validators: {
                    integer: {
                        message: 'The value is not an integer'
                    }
                }
            },
            'data[Advertisement][width]': {
                selector:"#width",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    },
                    integer: {
                        message: 'The value is not an integer'
                    }
                }
            },
            'data[Advertisement][height]': {
                selector:"#height",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    },
                    integer: {
                        message: 'The value is not an integer'
                    }
                }
            },
            'type': {
                selector:"#ads-type",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            },
            'data[Advertisement][video]': {
                selector:"#video",
                validators: {
                    notEmpty: {
                        message: 'The field is required'
                    }
                }
            }, 
        }
    });

});