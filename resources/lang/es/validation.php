<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |:attribute == El campo
    */

    'accepted'             => 'El campo debe ser aceptado.',
    'active_url'           => 'El campo no es una URL válida.',
    'after'                => 'El campo debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo sólo debe contener letras.',
    'alpha_dash'           => 'El campo sólo debe contener letras, números y guiones.',
    'alpha_num'            => 'El campo sólo debe contener letras y números.',
    'array'                => 'El campo debe ser un conjunto.',
    'before'               => 'El campo debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo tiene que estar entre :min - :max.',
        'file'    => 'El campo debe pesar entre :min - :max kilobytes.',
        'string'  => 'El campo tiene que tener entre :min - :max caracteres.',
        'array'   => 'El campo tiene que tener entre :min - :max ítems.',
    ],
    'boolean'              => 'El campo debe tener un valor verdadero o falso.',
    'confirmed'            => 'La confirmación del campo no coincide.',
    'date'                 => 'El campo no es una fecha válida.',
    'date_format'          => 'El campo no corresponde al formato :format.',
    'different'            => 'El campo y :other deben ser diferentes.',
    'digits'               => 'El campo debe tener máximo :digits dígitos.',
    'digits_between'       => 'El campo debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct'             => 'El campo contiene un valor duplicado.',
    'email'                => 'El campo no es un correo válido',
    'exists'               => 'El campo es inválido.',
    'file'                 => 'El campo debe ser un archivo.',
    'filled'               => 'El campo es obligatorio.',
    'gt'                   => [
        'numeric' => 'El campo debe ser mayor que :value.',
        'file'    => 'El campo debe tener más de :value kilobytes.',
        'string'  => 'El campo debe tener más de :value caracteres.',
        'array'   => 'El campo debe tener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'El campo debe ser como mínimo :value.',
        'file'    => 'El campo debe tener como mínimo :value kilobytes.',
        'string'  => 'El campo debe tener como mínimo :value caracteres.',
        'array'   => 'El campo debe tener como mínimo :value elementos.',
    ],
    'image'                => 'El campo debe ser una imagen.',
    'in'                   => 'El campo es inválido.',
    'in_array'             => 'El campo no existe en :other.',
    'integer'              => 'El campo debe ser un número entero.',
    'ip'                   => 'El campo debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo debe ser un dirección IPv4 válida',
    'ipv6'                 => 'El campo debe ser un dirección IPv6 válida.',
    'json'                 => 'El campo debe tener una cadena JSON válida.',
    'lt'                   => [
        'numeric' => 'El campo debe ser menor que :value.',
        'file'    => 'El campo debe tener menos de :value kilobytes.',
        'string'  => 'El campo debe tener menos de :value caracteres.',
        'array'   => 'El campo debe tener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'El campo debe ser como máximo :value.',
        'file'    => 'El campo debe tener como máximo :value kilobytes.',
        'string'  => 'El campo debe tener como máximo :value caracteres.',
        'array'   => 'El campo debe tener como máximo :value elementos.',
    ],
    'max'                  => [
        'numeric' => 'El campo no debe superar la cantidad de :max.',
        'file'    => 'El campo no debe ser mayor que :max kilobytes.',
        'string'  => 'El campo no debe ser mayor que :max caracteres.',
        'array'   => 'El campo no debe tener más de :max elementos.',
    ],
    'mimes'                => 'El campo debe ser un archivo con formato: :values.',
    'mimetypes'            => 'El campo debe ser un archivo con formato: :values.',
    'min'                  => [
        'numeric' => 'El campo debe ser de al menos :min.',
        'file'    => 'El tamaño del campo debe ser de al menos :min kilobytes.',
        'string'  => 'El campo debe contener al menos :min caracteres.',
        'array'   => 'El campo debe tener al menos :min elementos.',
    ],
    'not_in'               => 'El campo es inválido.',
    'not_regex'            => 'El formato del campo no es válido.',
    'numeric'              => 'El campo debe ser numérico.',
    'present'              => 'El campo debe estar presente.',
    'regex'                => 'El formato del campo es inválido.',
    'required'             => 'El campo es obligatorio.',
    'required_if'          => 'El campo es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo es obligatorio cuando ninguno de :values estén presentes.',
    'same'                 => 'El campo y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El tamaño del campo debe ser :size.',
        'file'    => 'El tamaño del campo debe ser :size kilobytes.',
        'string'  => 'El campo debe contener :size caracteres.',
        'array'   => 'El campo debe contener :size elementos.',
    ],
    'string'               => 'El campo debe ser una cadena de caracteres.',
    'timezone'             => 'El :attribute debe ser una zona válida.',
    'unique'               => 'El dato ya ha sido registrado.',
    'uploaded'             => 'La carga ha fallado.',
    'url'                  => 'El formato del campo es inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password' => [
            'min' => 'La :attribute debe contener más de :min caracteres',
        ],
        'email'    => [
            'unique' => 'El :attribute ya ha sido registrado.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'nombre',
        'username'              => 'usuario',
        'email'                 => 'correo electrónico',
        'first_name'            => 'nombre',
        'last_name'             => 'apellido',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de la contraseña',
        'city'                  => 'ciudad',
        'country'               => 'país',
        'address'               => 'dirección',
        'phone'                 => 'teléfono',
        'mobile'                => 'móvil',
        'age'                   => 'edad',
        'sex'                   => 'sexo',
        'gender'                => 'género',
        'year'                  => 'año',
        'month'                 => 'mes',
        'day'                   => 'día',
        'hour'                  => 'hora',
        'minute'                => 'minuto',
        'second'                => 'segundo',
        'title'                 => 'título',
        'content'               => 'contenido',
        'body'                  => 'contenido',
        'description'           => 'descripción',
        'excerpt'               => 'extracto',
        'date'                  => 'fecha',
        'time'                  => 'hora',
        'subject'               => 'asunto',
        'message'               => 'mensaje',
    ],
];
