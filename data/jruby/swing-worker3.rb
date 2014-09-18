include Java
import java.awt.BorderLayout
import java.awt.Color
import java.awt.Dimension
import java.util.Random
import java.util.Vector
import java.util.concurrent.Executors
import javax.swing.BorderFactory
import javax.swing.JButton
import javax.swing.JPanel
import javax.swing.JProgressBar
import javax.swing.JScrollPane
import javax.swing.JTable
import javax.swing.SwingWorker
import javax.swing.table.DefaultTableCellRenderer
import javax.swing.table.DefaultTableModel
import javax.swing.table.TableCellRenderer

PROGRESS_IDX = 2

class TestModel < DefaultTableModel
  @@number  = 0
  @@columns = {
    :name=>["No.", "Name", "Progress"],
    :editable=>[false, false, false],
    :class=>[java.lang.Integer, java.lang.String, java.lang.Integer],
  }
  def add_test(test, worker)
    self.add_row Vector.new([@@number, test.tname, test.progress])
    #self.add_row [@@number, test.tname, test.progress].to_java
    @@number = @@number+1
  end
  def getColumnCount
    return @@columns[:name].size
  end
  def getColumnName(modelIndex)
    return @@columns[:name][modelIndex]
  end
  def isCellEditable(row, col)
    return @@columns[:editable][col]
  end
  def getColumnClass(modelIndex)
    return @@columns[:class][modelIndex]
  end
end

class Test
  attr_accessor :tname
  attr_accessor :progress
  def initialize(tname = nil, progress = 0)
    self.tname = tname
    self.progress = java.lang.Integer.new(progress)
  end
end

class MainPanel < JPanel
  def initialize
    super BorderLayout.new
    model  = TestModel.new
    model.add_test Test.new("aaaa", 100), nil

    table  = JTable.new model
    table.auto_create_row_sorter = true
    table.fills_viewport_height  = true
    table.show_horizontal_lines  = false
    table.show_vertical_lines    = false
    table.intercell_spacing      = Dimension.new
    table.put_client_property "terminateEditOnFocusLost", true

    column = table.column_model.column 0
    column.max_width = 60
    column.min_width = 60
    column.resizable = false

    column = table.column_model.column PROGRESS_IDX
    bar = JProgressBar.new 0, 100
    bar.border = BorderFactory.createEmptyBorder 1,1,1,1
    renderer = table.get_default_renderer java.lang.Object
    column.set_cell_renderer do |table, obj, isSelected, hasFocus, row, column|
      text = "Done"
      if obj<0
        text = "Canceled"
      elsif obj<100
        bar.value = obj
        return bar
      end
      return renderer.getTableCellRendererComponent(
        table, text, isSelected, hasFocus, row, column)
    end
    executor = Executors.new_cached_thread_pool;
    button = JButton.new "add dummy task"
    button.add_action_listener do
      worker = ProgressWorker.new model
      #worker.addPropertyChangeListener ProgressListener.new(model)
      model.add_test Test.new("example", 0), worker
      #worker.execute
      executor.execute worker #JDK 1.6.0_18
    end
    self.add button, BorderLayout::SOUTH
    self.add JScrollPane.new(table)
    self.preferred_size = Dimension.new 320, 240
  end
end

class ProgressWorker < SwingWorker
  attr_accessor :model
  def initialize(model)
    super()
    @model = model
    @key = model.row_count
    @sleepDummy = 0.1
    @lengthOfTask = Random.new.nextInt(60) + 1
  end
  def doInBackground
    prev = 0
    current = 0
    while current<@lengthOfTask && !self.cancelled?
      current = current + 1
      begin
        sleep @sleepDummy
      rescue InterruptedException => e
        proxied_e = JavaUtilities.wrap e.cause
        proxied_e.print_stack_trace
        self.publish [-1].to_java(:Integer)
        break
      end
      #puts "#{@key}: #{i}"
      i = 100*current/@lengthOfTask
      self.publish [i].to_java :Integer
      #self.firePropertyChange("progress", prev, i)
      ##self.publish i
      prev = i
    end
    return @sleepDummy*@lengthOfTask
  end
  def process(chunks)
    chunks.each {|i|
      #@model.set_value_at i, @key, PROGRESS_IDX
      #Exception in thread "AWT-EventQueue-0" java.lang.ClassCastException:
      # java.lang.Byte cannot be cast to java.lang.Long
      @model.set_value_at java.lang.Integer.new(i), @key, PROGRESS_IDX
    }
  end
  def done
    text = nil
    i = -1
    if self.cancelled?
      text = "Canceled"
    else
      begin
        i = self.get
        text = "Done"
      rescue Exception => e
        text = "InterruptedException"
        proxied_e = JavaUtilities.wrap e.cause
        proxied_e.print_stack_trace
      end
    end
    puts "#{@key}: #{text} : #{i}s"
  end
end

import javax.swing.UIManager
import javax.swing.WindowConstants
def create_and_show_GUI
  begin
    UIManager.look_and_feel = UIManager.system_look_and_feel_class_name
  rescue Exception => e
    proxied_e = JavaUtilities.wrap e.cause
    proxied_e.print_stack_trace
  end
  frame = javax.swing.JFrame.new "JRuby Swing JTable"
  frame.default_close_operation = WindowConstants::EXIT_ON_CLOSE
  frame.content_pane.add MainPanel.new
  frame.pack
  frame.location_relative_to = nil
  frame.visible = true
end
def run; create_and_show_GUI; end
java.awt.EventQueue.invokeLater self
