import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
public class PopupMenuLocationTest {
  public JComponent makeUI() {
    JPopupMenu pop = new JPopupMenu();
    pop.add("aaaaaa");
    pop.add("bbb");
    pop.add("cccccc");
    pop.add("dddddddd");

    JPanel p = new JPanel(new BorderLayout());
    p.setBorder(BorderFactory.createEmptyBorder(5,5,5,5));

    Box n = Box.createHorizontalBox();
    n.add(new JButton(new ShowPopupAction(pop, Location.NW)));
    n.add(Box.createHorizontalGlue());
    n.add(new JButton(new ShowPopupAction(pop, Location.NE)));

    Box s = Box.createHorizontalBox();
    s.add(new JButton(new ShowPopupAction(pop, Location.SW)));
    s.add(Box.createHorizontalGlue());
    s.add(new JButton(new ShowPopupAction(pop, Location.SE)));

    p.add(n, BorderLayout.NORTH);
    p.add(s, BorderLayout.SOUTH);
    return p;
  }
  public static void main(String[] args) {
    EventQueue.invokeLater(new Runnable() {
      @Override public void run() {
        createAndShowGUI();
      }
    });
  }
  public static void createAndShowGUI() {
    JFrame f = new JFrame();
    f.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
    f.getContentPane().add(new PopupMenuLocationTest().makeUI());
    f.setSize(320, 240);
    f.setLocationRelativeTo(null);
    f.setVisible(true);
  }
}
enum Location { NW, NE, SW, SE }
class ShowPopupAction extends AbstractAction {
  private final JPopupMenu pop;
  private final Location loc;
  public ShowPopupAction(JPopupMenu pop, Location loc) {
    super(loc.toString());
    this.pop = pop;
    this.loc = loc;
  }
  @Override public void actionPerformed(ActionEvent e) {
    JButton b = (JButton)e.getSource();
    switch(loc) {
    case NW:
      pop.show(b, 0, b.getHeight());
      break;
    case NE:
      pop.show(b, b.getWidth()-pop.getPreferredSize().width,
                  b.getHeight());
      break;
    case SW:
      pop.show(b, 0, -pop.getPreferredSize().height);
      break;
    case SE:
      pop.show(b, b.getWidth()-pop.getPreferredSize().width,
                  -pop.getPreferredSize().height);
      break;
    }
  }
}
