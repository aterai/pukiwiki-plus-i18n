include Java
import javax.swing.JButton
import javax.swing.JFrame
import javax.swing.JPanel
import javax.swing.JTextField
import javax.swing.WindowConstants

def makeUI
  field  = JTextField.new 16
  button = JButton.new "add a"
  button.add_action_listener {|e|
    field.text = "#{e.get_when}"
  }
  p = JPanel.new
  p.add field
  p.add button
  return p
end
def run
  f = JFrame.new "JRuby Swing JButton ActionListener"
  f.default_close_operation = WindowConstants::EXIT_ON_CLOSE
  f.add makeUI
  f.set_size 320, 240
  f.location_relative_to = nil
  f.visible = true
end
java.awt.EventQueue.invokeLater self

# class MainPanel < javax.swing.JPanel
#   def initialize
#     super
#     field = javax.swing.JTextField.new 32
#     button = javax.swing.JButton.new "add a"
#     button.add_action_listener { #|e|
#       field.text = field.text + "a"
#     }
# #     button.add_action_listener do |e|
# #       field.text = field.text + "a"
# #     end
#     self.add field
#     self.add button
#   end
# end
#
# # #JRuby 1.0
# # class MainPanel < javax.swing.JPanel
# #   include java.awt.event.ActionListener
# #   def initialize
# #     super
# #     @field = javax.swing.JTextField.new 26
# #     button = javax.swing.JButton.new "add a"
# #     button.add_action_listener self
# #     self.add @field
# #     self.add button
# #   end
# #   def actionPerformed(event)
# #     #puts event.to_string
# #     @field.text = @field.text + "a"
# #   end
# # end
#
# import javax.swing.UIManager
# import javax.swing.WindowConstants
# def create_and_show_GUI
#   begin
#     UIManager.look_and_feel = UIManager.system_look_and_feel_class_name
#   rescue Exception => e
#     proxied_e = JavaUtilities.wrap e.cause
#     proxied_e.print_stack_trace
#   end
#   frame = javax.swing.JFrame.new "JRuby Swing JButton ActionListener"
#   frame.default_close_operation = WindowConstants::EXIT_ON_CLOSE
#   frame.content_pane.add MainPanel.new
#   frame.pack
#   frame.location_relative_to = nil
#   frame.visible = true
# end
# def run
#   create_and_show_GUI
# end
# java.awt.EventQueue.invokeLater self
