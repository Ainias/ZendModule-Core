<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 06.01.17
 * Time: 15:52
 */

namespace Ainias\Core\Datatable;

class SimpleDatatable extends Datatable
{
    public function __construct($id, array $options = array())
    {
        parent::__construct($id, $options);
        $this->setPaginate(false);
        $this->setSortable(false);
    }

    protected function buildJsInitObject()
    {
        $pageLength = $this->getPageLength();
        $lengthMenu = $this->getLengthMenu();
        $lengthMenu = "[[" . implode(", ", $lengthMenu) . "], [\"" . implode("\",\"", array_keys($lengthMenu)) . "\"]]";
        $paginate = ($this->isPaginate()) ? "true" : "false";
        $responsive = "false";
        if ($this->isResponsive()) {
            $renderer = $this->getResponsiveRenderer();
            $responsive =
                <<<JS
                {
                details: {
                    renderer: $renderer
                }
            }
JS;
        }

        $scriptColumns = $this->buildJsColumns();

        $script = <<<JS
		var dataTableInitObject = {
					oLanguage :{
				sEmptyTable: "<b>Keine Daten vorhanden.</b>",
				sInfoEmpty: "Keine Einträge vorhanden",
				sInfoFiltered: "(gefiltert von _MAX_ Einträgen)",
				sInfoThousands: ".",
				sLengthMenu: "Zeige _MENU_ Einträge",
				sZeroRecords: "Keine Einträge gefunden",
				sSearch: "Suche:",
				emptyTable: "Keine Daten vorhanden",
				oPaginate: {
					sNext: "Nächste Seite",
					sPrevious: "Vorherige Seite"
				}
			},
			bFilter: false,
			bInfo: false,
			responsive: $responsive,
			 dom: '<"top"lpf<"clear">>rt<"bottom"ip<"clear">>'
					};
					dataTableInitObject.pageLength = $pageLength;
		dataTableInitObject.lengthMenu = $lengthMenu;
		dataTableInitObject.bPaginate = $paginate;
		dataTableInitObject.aoColumnDefs = [$scriptColumns];
JS;
        return $script;
    }
}