<?php

namespace Database\Seeders;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            'new_rota'=>'New Rota','rotas_time_change'=>'Rotas Time Change','cancel_rotas'=>'Cancel Rotas','days_off'=>'Days Off','new_availability'=>'New Availability'
        ];
        $types = [
            'slack','telegram'
        ];

        $defaultTemplate = [
            'slack' => [
                'new_rota' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'Rotated جديد تم تكوينه بواسطة : {company_name}',
                            'da' => 'New Rotas oprettet af : {company_name}',
                            'de' => 'Neue Rotas, die von der : {company_name}',
                            'en' => 'New Rotas created by the : {company_name}',
                            'es' => 'Nuevas Rotas creadas por la : {company_name}',
                            'fr' => 'Nouvelles Rotas créées par : {company_name}',
                            'it' => 'Nuova Rotas creata dal : {company_name}',
                            'ja' => '次のものによって作成された新規差分 : {company_name}',
                            'nl' => 'Nieuwe Rotas gemaakt door de : {company_name}',
                            'pl' => 'Nowe Rotas utworzone przez : {company_name}',
                            'ru' => 'Новые Rotas, созданные : {company_name}',
                            'pt' => 'Novo Rotas criadas pelo : {company_name}',
                        ]
                ],
                'rotas_time_change' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Start Time": "start_time",
                        "End Time": "end_time",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تغيير وقت Rotated الى : {start_time} - {end_time}',
                            'da' => 'Rotas-tid ændret til : {start_time} - {end_time}',
                            'de' => 'Rotas-Zeit wurde in die : {start_time} - {end_time}',
                            'en' => 'Rotas time changed to the : {start_time} - {end_time}',
                            'es' => 'Rotas el tiempo cambiado a la : {start_time} - {end_time}',
                            'fr' => 'Le temps de rotation a été remplacé par : {start_time} - {end_time}',
                            'it' => 'Il tempo di rotazione è cambiato in : {start_time} - {end_time}',
                            'ja' => 'ロータス時間が変更されました : {start_time} - {end_time}',
                            'nl' => 'Rotas-tijd gewijzigd in : {start_time} - {end_time}',
                            'pl' => 'Rotacja została zmieniona na : {start_time} - {end_time}',
                            'ru' => 'Время Ротаса изменено на : {start_time} - {end_time}',
                            'pt' => 'O tempo de rotas mudou para o : {start_time} - {end_time}',
                        ]
                ],
                'cancel_rotas' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'دوران دوران بواسطة : {company_name}',
                            'da' => 'Rotas kan genvindes af : {company_name}',
                            'de' => 'Rotas cancled durch die : {company_name}',
                            'en' => 'Rotas cancled by the : {company_name}',
                            'es' => 'Rotas cancladas por el : {company_name}',
                            'fr' => 'Rotas canclés par le : {company_name}',
                            'it' => 'Rotas cantato dal : {company_name}',
                            'ja' => 'ロータスは、以下のようにしている : {company_name}',
                            'nl' => 'Rotas gecancled door de : {company_name}',
                            'pl' => 'Obróci się w prawo : {company_name}',
                            'ru' => 'Rotas : {company_name}',
                            'pt' => 'Rotas cancladas pelo : {company_name}',
                        ]
                ],
                'days_off' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'اليوم الذي تم ايقافه حتى تاريخه : {rota_date}',
                            'da' => 'Dag off til dato : {rota_date}',
                            'de' => 'Tag ab dem Datum : {rota_date}',
                            'en' => 'Day off to date the : {rota_date}',
                            'es' => 'Día apagado hasta la fecha : {rota_date}',
                            'fr' => 'Jour de congé jusqu à ce jour : {rota_date}',
                            'it' => 'Giorno libero per data la : {rota_date}',
                            'ja' => '日付を指定してオフにする : {rota_date}',
                            'nl' => 'Dag uit tot en met : {rota_date}',
                            'pl' => 'Dzień wolny od daty : {rota_date}',
                            'ru' => 'День с даты начала : {rota_date}',
                            'pt' => 'Dia de folga para namorar o : {rota_date}',
                        ]
                ],
                'new_availability' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم اضافة نموذج الاتاحة بواسطة : {company_name}',
                            'da' => 'Tilgængelighedsmønsteret er tilføjet af : {company_name}',
                            'de' => 'Das Verfügbarkeitsmuster wurde von der : {company_name}',
                            'en' => 'Availability pattern has been added by the : {company_name}',
                            'es' => 'El patrón de disponibilidad se ha añadido mediante : {company_name}',
                            'fr' => 'Le modèle de disponibilité a été ajouté par : {company_name}',
                            'it' => 'Lo schema di disponibilità è stato aggiunto dal : {company_name}',
                            'ja' => '可用性パターンが以下のように追加され : {company_name}',
                            'nl' => 'Beschikbaarheidspatroon is toegevoegd door de : {company_name}',
                            'pl' => 'Wzorzec dostępności został dodany przez : {company_name}',
                            'ru' => 'Шаблон доступности добавлен в : {company_name}',
                            'pt' => 'Padrão de disponibilidade foi adicionado pelo : {company_name}',
                        ]
                ],
            ],
            'telegram' => [
                'new_rota' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'Rotated جديد تم تكوينه بواسطة : {company_name}',
                            'da' => 'New Rotas oprettet af : {company_name}',
                            'de' => 'Neue Rotas, die von der : {company_name}',
                            'en' => 'New Rotas created by the : {company_name}',
                            'es' => 'Nuevas Rotas creadas por la : {company_name}',
                            'fr' => 'Nouvelles Rotas créées par : {company_name}',
                            'it' => 'Nuova Rotas creata dal : {company_name}',
                            'ja' => '次のものによって作成された新規差分 : {company_name}',
                            'nl' => 'Nieuwe Rotas gemaakt door de : {company_name}',
                            'pl' => 'Nowe Rotas utworzone przez : {company_name}',
                            'ru' => 'Новые Rotas, созданные : {company_name}',
                            'pt' => 'Novo Rotas criadas pelo : {company_name}',
                        ]
                ],
                'rotas_time_change' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Start Time": "start_time",
                        "End Time": "end_time",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تغيير وقت Rotated الى : {start_time} - {end_time}',
                            'da' => 'Rotas-tid ændret til : {start_time} - {end_time}',
                            'de' => 'Rotas-Zeit wurde in die : {start_time} - {end_time}',
                            'en' => 'Rotas time changed to the : {start_time} - {end_time}',
                            'es' => 'Rotas el tiempo cambiado a la : {start_time} - {end_time}',
                            'fr' => 'Le temps de rotation a été remplacé par : {start_time} - {end_time}',
                            'it' => 'Il tempo di rotazione è cambiato in : {start_time} - {end_time}',
                            'ja' => 'ロータス時間が変更されました : {start_time} - {end_time}',
                            'nl' => 'Rotas-tijd gewijzigd in : {start_time} - {end_time}',
                            'pl' => 'Rotacja została zmieniona na : {start_time} - {end_time}',
                            'ru' => 'Время Ротаса изменено на : {start_time} - {end_time}',
                            'pt' => 'O tempo de rotas mudou para o : {start_time} - {end_time}',
                        ]
                ],
                'cancel_rotas' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'دوران دوران بواسطة : {company_name}',
                            'da' => 'Rotas kan genvindes af : {company_name}',
                            'de' => 'Rotas cancled durch die : {company_name}',
                            'en' => 'Rotas cancled by the : {company_name}',
                            'es' => 'Rotas cancladas por el : {company_name}',
                            'fr' => 'Rotas canclés par le : {company_name}',
                            'it' => 'Rotas cantato dal : {company_name}',
                            'ja' => 'ロータスは、以下のようにしている : {company_name}',
                            'nl' => 'Rotas gecancled door de : {company_name}',
                            'pl' => 'Obróci się w prawo : {company_name}',
                            'ru' => 'Rotas : {company_name}',
                            'pt' => 'Rotas cancladas pelo : {company_name}',
                        ]
                ],
                'days_off' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Rota Date":"rota_date",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'اليوم الذي تم ايقافه حتى تاريخه : {rota_date}',
                            'da' => 'Dag off til dato : {rota_date}',
                            'de' => 'Tag ab dem Datum : {rota_date}',
                            'en' => 'Day off to date the : {rota_date}',
                            'es' => 'Día apagado hasta la fecha : {rota_date}',
                            'fr' => 'Jour de congé jusqu à ce jour : {rota_date}',
                            'it' => 'Giorno libero per data la : {rota_date}',
                            'ja' => '日付を指定してオフにする : {rota_date}',
                            'nl' => 'Dag uit tot en met : {rota_date}',
                            'pl' => 'Dzień wolny od daty : {rota_date}',
                            'ru' => 'День с даты начала : {rota_date}',
                            'pt' => 'Dia de folga para namorar o : {rota_date}',
                        ]
                ],
                'new_availability' => [
                    'variables' => '{
                        "Employee": "employee_name",
                        "Email": "email",
                        "Company Name": "company_name",
                        "App Name": "app_name",
                        "App Url": "app_url",
                        }',
                        'lang' => [
                            'ar' => 'تم اضافة نموذج الاتاحة بواسطة : {company_name}',
                            'da' => 'Tilgængelighedsmønsteret er tilføjet af : {company_name}',
                            'de' => 'Das Verfügbarkeitsmuster wurde von der : {company_name}',
                            'en' => 'Availability pattern has been added by the : {company_name}',
                            'es' => 'El patrón de disponibilidad se ha añadido mediante : {company_name}',
                            'fr' => 'Le modèle de disponibilité a été ajouté par : {company_name}',
                            'it' => 'Lo schema di disponibilità è stato aggiunto dal : {company_name}',
                            'ja' => '可用性パターンが以下のように追加され : {company_name}',
                            'nl' => 'Beschikbaarheidspatroon is toegevoegd door de : {company_name}',
                            'pl' => 'Wzorzec dostępności został dodany przez : {company_name}',
                            'ru' => 'Шаблон доступности добавлен в : {company_name}',
                            'pt' => 'Padrão de disponibilidade foi adicionado pelo : {company_name}',
                        ]
                ],
            ]
        ];

        foreach($types as $t)
        {
            foreach($notifications as $k => $n)
            {
                $ntfy = NotificationTemplates::where('slug',$k)->where('type',$t)->count();
                if($ntfy == 0)
                {
                    $new = new NotificationTemplates();
                    $new->name = $n;
                    $new->type = $t;
                    $new->slug = $k;
                    $new->save();

                    foreach($defaultTemplate[$t][$k]['lang'] as $lang => $content)
                    {
                        NotificationTemplateLangs::create(
                            [
                                'parent_id' => $new->id,
                                'lang' => $lang,
                                'variables' => $defaultTemplate[$t][$k]['variables'],
                                'content' => $content,
                                // supar admin get and set that id
                                'created_by' => 1,
                            ]
                        );
                    }
                }
            }
        }
    }
}
