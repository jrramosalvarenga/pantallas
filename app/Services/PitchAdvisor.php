<?php

namespace App\Services;

class PitchAdvisor
{
    public static function getAdvice(string $category): array
    {
        $category = strtolower(trim($category));
        $map = self::adviceMap();

        foreach ($map as $key => $advice) {
            if (str_contains($category, $key)) {
                return $advice;
            }
        }

        return [
            'tip' => 'Investiga el negocio antes de visitarlo. Adapta tu propuesta a sus necesidades específicas.',
            'approach' => 'Preséntate profesionalmente, muestra casos de éxito similares y ofrece una prueba o demo.',
            'opening' => '¡Hola! Soy de Kontanos Publicidad. Notamos que su negocio tiene gran potencial para atraer más clientes con publicidad en pantallas LED.',
            'objections' => [
                'Es muy caro' => 'Comparado con otros medios, nuestras pantallas tienen mayor impacto visual y costo por impresión más bajo.',
                'No necesito publicidad' => 'Entiendo, pero sus competidores ya están invirtiendo en visibilidad. Le mostramos resultados reales.',
                'Ya tengo mi publicidad' => 'Excelente, las pantallas LED complementan cualquier estrategia. Podemos hacer una prueba sin compromiso.',
            ],
        ];
    }

    private static function adviceMap(): array
    {
        return [
            'comida' => [
                'tip' => 'Restaurantes y comedores dependen del tráfico local. Muestra cómo las pantallas atraen clientes que pasan por la zona.',
                'approach' => 'Visita en horario tranquilo (2-4 PM). Lleva fotos de anuncios de comida en pantallas LED. Habla de promociones del día.',
                'opening' => '¿Se ha fijado cuánta gente pasa frente a nuestra pantalla? Imagine su menú del día ahí, justo a la hora del almuerzo.',
                'objections' => [
                    'Ya tengo mi clientela' => 'Perfecto, pero ¿no le gustaría que esos clientes vinieran más seguido? Podemos anunciar sus promociones diarias.',
                    'Es muy caro' => 'Por menos de lo que cuesta un rótulo, su menú se ve miles de veces al día. ¿Cuántos platos extra necesita vender para que se pague solo?',
                ],
            ],
            'salud' => [
                'tip' => 'Clínicas y farmacias necesitan proyectar confianza y profesionalismo. La pantalla LED da visibilidad premium.',
                'approach' => 'Agenda cita formal. Habla de posicionamiento de marca y cómo la publicidad digital genera confianza en pacientes.',
                'opening' => 'Doctor(a), las clínicas que invierten en visibilidad profesional atraen pacientes que valoran la calidad. ¿Le gustaría destacar sus especialidades?',
                'objections' => [
                    'Los pacientes vienen por referencia' => 'Las referencias son excelentes, pero la visibilidad refuerza la confianza. Cuando alguien busca, ¿le gustaría ser la primera opción que vean?',
                    'No es ético anunciarme' => 'Muchos profesionales de salud anuncian sus servicios de forma informativa. No es vender, es educar a la comunidad sobre sus opciones.',
                ],
            ],
            'finanza' => [
                'tip' => 'Bancos, cooperativas y financieras buscan presencia de marca constante. Enfócate en cobertura y frecuencia.',
                'approach' => 'Contacta al gerente de mercadeo. Presenta datos de tráfico vehicular y peatonal cerca de la pantalla.',
                'opening' => 'Nuestras pantallas están en puntos de alto tráfico. ¿Su institución ya tiene presencia digital en las calles principales?',
                'objections' => [
                    'Ya tenemos nuestro presupuesto asignado' => 'Entiendo. ¿Podríamos agendar para cuando planifiquen el próximo trimestre? Le dejo una propuesta para que la evalúen.',
                    'Usamos otros medios' => 'Las pantallas LED complementan perfectamente sus campañas de radio y redes. Es el medio que no se puede ignorar al pasar.',
                ],
            ],
            'construc' => [
                'tip' => 'Ferreterías y constructoras tienen productos visuales perfectos para pantallas. Muestra catálogos dinámicos.',
                'approach' => 'Visita directamente. Muestra cómo pueden anunciar ofertas semanales y materiales nuevos.',
                'opening' => '¿Sabía que los negocios de construcción que anuncian sus ofertas en pantallas LED ven un incremento en ventas de fin de semana?',
                'objections' => [
                    'Mi negocio ya es conocido' => 'Ser conocido es genial, pero recordar sus precios y ofertas es lo que trae al cliente de vuelta cada semana.',
                ],
            ],
            'agro' => [
                'tip' => 'Agropecuarias y veterinarias tienen temporadas altas. Aprovecha ciclos de siembra y vacunación para proponer campañas.',
                'approach' => 'Visita antes de temporada alta. Habla de cómo pueden anunciar productos de temporada y servicios veterinarios.',
                'opening' => 'Se acerca la temporada de [siembra/vacunación]. ¿Le gustaría que los productores de la zona vean sus productos al pasar?',
                'objections' => [
                    'Mis clientes ya saben dónde encontrarme' => 'Sí, pero los nuevos productores y los que llegan de otras zonas no. La pantalla le da alcance que el boca a boca no tiene.',
                ],
            ],
            'educa' => [
                'tip' => 'Escuelas y universidades tienen períodos de matrícula. Planifica campañas alrededor de esas fechas.',
                'approach' => 'Contacta al director o encargado de admisiones. Ofrece paquetes durante período de inscripción.',
                'opening' => 'Se acercan las matrículas. ¿Ya tienen su campaña de captación de alumnos? Nuestras pantallas cubren las zonas donde transitan los padres de familia.',
                'objections' => [
                    'Usamos redes sociales' => 'Las redes son excelentes pero no alcanzan a todos los padres. La pantalla LED complementa con presencia física constante.',
                ],
            ],
            'moto' => [
                'tip' => 'Talleres de motos y ventas de repuestos son negocios de impulso visual. Los clientes deciden al pasar.',
                'approach' => 'Visita directamente. Muestra cómo anuncios con precios de aceites, llantas y servicios generan tráfico inmediato.',
                'opening' => '¿Cuántas motos pasan frente a nuestra pantalla al día? Imagine sus precios de cambio de aceite ahí. El cliente viene directo.',
                'objections' => [
                    'Mi negocio es pequeño' => 'Los negocios pequeños son los que más se benefician. Por una inversión accesible, miles de personas ven sus servicios.',
                ],
            ],
            'comercio' => [
                'tip' => 'Tiendas y comercios generales necesitan rotación constante de anuncios con promociones semanales.',
                'approach' => 'Ofrece paquetes con cambio frecuente de arte. Muestra cómo pueden anunciar diferentes productos cada semana.',
                'opening' => '¿Le gustaría anunciar sus mejores ofertas de la semana donde miles de personas las vean? Podemos cambiar su anuncio cuantas veces quiera.',
                'objections' => [
                    'Pongo mis ofertas en la vitrina' => 'La vitrina la ven quienes pasan enfrente. La pantalla la ven desde cuadras de distancia y a toda hora.',
                ],
            ],
            'gasolinera' => [
                'tip' => 'Las gasolineras tienen audiencia cautiva. Minutos de espera = oportunidad de impacto publicitario.',
                'approach' => 'Habla con el administrador. Propón que anuncien sus servicios adicionales: tienda, lavado, lubricentro.',
                'opening' => 'Miles de conductores pasan por su estación diariamente. ¿Sabían que pueden promocionar su tienda y servicios de lavado en nuestra pantalla?',
                'objections' => [
                    'La gasolinera se vende sola' => 'El combustible sí, pero ¿cuántos clientes saben de su tienda de conveniencia o lubricentro? La pantalla les recuerda.',
                ],
            ],
            'florist' => [
                'tip' => 'Florerías tienen picos en fechas especiales. Planifica campañas para San Valentín, Día de la Madre, etc.',
                'approach' => 'Visita semanas antes de fechas especiales. Ofrece paquetes cortos de alto impacto.',
                'opening' => 'Se acerca [fecha especial]. ¿Ya tiene su campaña lista? Podemos poner sus arreglos más bonitos donde todos los vean.',
                'objections' => [
                    'Solo vendo en fechas especiales' => 'Precisamente por eso necesita máximo impacto en poco tiempo. Dos semanas en pantalla le llenan la agenda.',
                ],
            ],
            'hospedaje' => [
                'tip' => 'Hoteles y hostales necesitan visibilidad para viajeros. Enfócate en ubicación y servicios.',
                'approach' => 'Contacta al gerente. Muestra cómo turistas y viajeros de negocios ven las pantallas al llegar a la ciudad.',
                'opening' => 'Cuando un viajero llega a la ciudad, ¿dónde ve primero dónde hospedarse? En las pantallas de las calles principales.',
                'objections' => [
                    'Estamos en Booking y Google' => 'Excelente para reservas previas. Pero el viajero de último momento busca opciones al llegar. Ahí es donde entramos.',
                ],
            ],
            'gimnasio' => [
                'tip' => 'Gimnasios tienen su pico en enero y después de vacaciones. Anticipa campañas de inscripción.',
                'approach' => 'Visita al dueño. Habla de cómo la publicidad visual motiva a la gente a inscribirse por impulso.',
                'opening' => '¿Sabía que la mayoría de inscripciones a gimnasios son por impulso? Imagine su gimnasio anunciándose donde miles de personas lo ven cada día.',
                'objections' => [
                    'Uso Instagram para promocionarme' => 'Instagram es genial para engagement, pero la pantalla LED genera el impulso de "lo veo, me inscribo". Son complementarios.',
                ],
            ],
            'celular' => [
                'tip' => 'Tiendas de celulares y reparación tienen alta competencia. Diferénciate mostrando precios y promociones.',
                'approach' => 'Visita directamente. Muestra cómo pueden anunciar ofertas de equipos y reparaciones con precios.',
                'opening' => 'Con tanta competencia en celulares, ¿cómo se diferencia de los demás? Nuestra pantalla pone sus mejores precios donde todos los ven.',
                'objections' => [
                    'Tengo mucha competencia' => 'Exactamente por eso necesita estar visible. El que se anuncia primero, vende primero.',
                ],
            ],
            'repuesto' => [
                'tip' => 'Tiendas de repuestos tienen clientes que buscan por necesidad. Anunciar disponibilidad y precios es clave.',
                'approach' => 'Visita directamente. Habla de cómo los conductores buscan repuestos al pasar por la zona.',
                'opening' => 'Cuando alguien necesita un repuesto, busca cerca. ¿No le gustaría que su tienda sea la primera que vean?',
                'objections' => [
                    'Mis clientes ya me conocen' => 'Sus clientes actuales sí, pero cada día hay nuevos conductores buscando. La pantalla le trae clientes nuevos.',
                ],
            ],
            'plaza' => [
                'tip' => 'Plazas comerciales pueden anunciar a todos sus locatarios. Ofrece paquetes compartidos.',
                'approach' => 'Contacta al administrador. Propón un espacio compartido donde todos los locales se beneficien.',
                'opening' => '¿Le gustaría que su plaza comercial sea el destino favorito de compras? Podemos anunciar sus locales y promociones en pantalla LED.',
                'objections' => [
                    'Cada local hace su propia publicidad' => 'Un anuncio conjunto de la plaza atrae al cliente. Ya adentro, cada local compite por su atención. Todos ganan.',
                ],
            ],
            'politic' => [
                'tip' => 'Campañas políticas necesitan máxima visibilidad en poco tiempo. Ofrece paquetes intensivos.',
                'approach' => 'Contacta al equipo de campaña. Ofrece cobertura en múltiples pantallas con cambio frecuente de mensaje.',
                'opening' => '¿Ya tienen cubierta su publicidad exterior? Nuestras pantallas LED son el medio más visible y moderno para su campaña.',
                'objections' => [
                    'Ya tenemos vallas' => 'Las vallas son estáticas. Las pantallas LED llaman más la atención, se ven de noche, y puede cambiar el mensaje cuando quiera.',
                ],
            ],
            'abogad' => [
                'tip' => 'Bufetes de abogados necesitan proyectar profesionalismo. Enfócate en posicionamiento de marca.',
                'approach' => 'Agenda cita formal. Habla de cómo la visibilidad genera confianza y posiciona al bufete como referente.',
                'opening' => 'Licenciado(a), ¿su bufete es tan visible como su reputación? Nuestras pantallas posicionan su marca donde los tomadores de decisión transitan.',
                'objections' => [
                    'Los clientes vienen por referencia' => 'Las referencias se refuerzan con visibilidad. Cuando alguien menciona su nombre y luego lo ve en pantalla, la confianza se duplica.',
                ],
            ],
            'bienes' => [
                'tip' => 'Inmobiliarias tienen propiedades visuales. Muestra fotos de casas y terrenos en la pantalla para generar leads.',
                'approach' => 'Contacta al corredor principal. Ofrece mostrar sus propiedades destacadas con fotos y precios.',
                'opening' => '¿Cuántos compradores potenciales pasan frente a nuestra pantalla? Imagine sus mejores propiedades ahí, con foto y precio. El teléfono no para de sonar.',
                'objections' => [
                    'Uso portales inmobiliarios' => 'Los portales son para quienes ya están buscando. La pantalla genera interés en quienes ni sabían que querían comprar.',
                ],
            ],
            'supermercado' => [
                'tip' => 'Supermercados necesitan anunciar ofertas semanales. Ofrece rotación frecuente de arte.',
                'approach' => 'Habla con el gerente. Propón anunciar las ofertas de la semana y productos estrella.',
                'opening' => '¿Le gustaría que toda la ciudad conozca sus ofertas de la semana antes de ir a comprar? Nuestra pantalla se lo hace posible.',
                'objections' => [
                    'Repartimos volantes' => 'Los volantes terminan en la basura. La pantalla se ve miles de veces al día sin generar desperdicio. Y se actualiza al instante.',
                ],
            ],
        ];
    }
}
