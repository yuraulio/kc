<?php

namespace App\Console\Commands;

use App\Model\Admin\Page;
use App\Model\Admin\Template;
use Illuminate\Console\Command;
use stdClass;

class ComponentsRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CMS-components-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh components saves in pages and templates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $components = json_decode(file_get_contents(base_path() . "/resources/js/components/template-components.json"), false);

        // for pages
        $pages = Page::withoutGlobalScopes()->get();
        foreach ($pages as $page) {
            $content = $page->content;
            
            $content = $this->refreshContent($content, $components);

            $page->content = $content;
            $page->save();

            $this->info("page $page->id done");
        }

        // for templates
        $templates = Template::get();
        foreach ($templates as $template) {
            $content = $template->rows;
            
            $content = $this->refreshContent($content, $components);

            $template->rows = $content;
            $template->save();

            $this->info("template $template->id done");
        }

        $this->info("components refresh finished");
    }

    private function refreshContent($content, $components)
    {
        $rows = json_decode($content);
        foreach ($rows as $row_key => $row) {
            foreach ($row->columns as $column_key => $column) {
                $this->info("found column");
                $component_id = $column->id;
                $component_order = $column->order;
                $component = $column->component;
                $component_active = $column->active;
                $component_dynamic = $column->template->dynamic ?? false;
                $inputs = $column->template->inputs;

                $new_column = new stdClass();
                $new_column->id = $component_id;
                $new_column->order = $component_order;
                $new_column->component = $component;
                $new_column->active = $component_active;
                $new_column->template = json_decode(json_encode($components->$component));
                if (isset($new_column->template->dynamic)) {
                    $new_column->template->dynamic = $component_dynamic;
                }

                foreach ($new_column->template->inputs as $input_key => $new_input) {
                    $key = array_search($new_input->key, array_column($inputs, 'key'));

                    if ($key !== false) {
                        $this->info("found old value in column");
                        if (property_exists($inputs[$key], "value")) {
                            $old_value = $inputs[$key]->value;
                            $new_column->template->inputs[$input_key]->value = $old_value;

                            if ($new_input->key == "tabs") {
                                $old_tabs = $inputs[$key]->tabs;
                                $new_column->template->inputs[$input_key]->tabs = $old_tabs;
                            }
                        }
                    }
                }

                $rows[$row_key]->columns[$column_key] = $new_column;
                $rows[$row_key]->tab = json_decode(json_encode($components->$component))->tab;
                $this->info("saved row $row_key, column $column_key");
            }
        }

        return json_encode($rows);
    }
}
