include Java
import java.awt.BorderLayout
import java.awt.Dimension
import javax.swing.BorderFactory
import javax.swing.JButton
import javax.swing.JPanel
import javax.swing.JProgressBar
import javax.swing.SwingWorker

class ProgressListener
  include java.beans.PropertyChangeListener
  attr_accessor :progress
  def initialize(progress)
    self.progress = progress
    self.progress.set_value 0
  end
  def propertyChange(evt)
    strPropertyName = evt.property_name
    if strPropertyName=="progress" then
      nv = evt.new_value
      progress.set_value nv
    end
  end
end

class ProgressWorker < SwingWorker
  attr_accessor :button
  def initialize(button)
    super()
    self.button = button
    button.enabled = false
  end
  def doInBackground
    puts "doInBackground"
    lengthOfTask = 120
    current = 0
    prev = 0
    while current<=lengthOfTask && !self.cancelled?
      begin
        sleep 0.1
      rescue InterruptedException => e
        proxied_e = JavaUtilities.wrap e.cause
        proxied_e.print_stack_trace
        break
      end
      new_value = 100*current/lengthOfTask
      self.firePropertyChange("progress", prev, new_value)
      #self.setProgress(100*current/lengthOfTask)
      current = current + 1
      prev = new_value
    end
    return "Done"
  end
  def done
    text = nil
    if self.cancelled?
      text = "Canceled"
    else
      begin
        text = self.get
      rescue Exception => e
        text = "InterruptedException"
        #proxied_e = JavaUtilities.wrap e.cause
        #e.print_stack_trace
      end
    end
    button.enabled = true
    puts text
  end
end

class MainPanel < JPanel
  def initialize
    super BorderLayout.new
    bar    = JProgressBar.new 0, 100
    button = JButton.new "start"
    button.add_action_listener {
      w = ProgressWorker.new button
      w.addPropertyChangeListener ProgressListener.new(bar)
      w.execute
    }
    self.add bar, BorderLayout::NORTH
    self.add button, BorderLayout::SOUTH
    self.border = BorderFactory.create_empty_border 5,5,5,5
    self.preferred_size = Dimension.new 320, 60
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
  frame = javax.swing.JFrame.new "JRuby Swing JProgressBar"
  frame.default_close_operation = WindowConstants::EXIT_ON_CLOSE
  frame.content_pane.add MainPanel.new
  frame.pack
  frame.location_relative_to = nil
  frame.visible = true
end
def run
  create_and_show_GUI
end
java.awt.EventQueue.invokeLater self
