//I think the same way can use for JPopupMenu and JMenuBar: <a href="http://terai.xrea.jp/data/swing/TranslucentMenuTest2.java">TranslucentMenuTest2.java</a>
//(using JDK 1.7.0_04 on Windows 7)
//package example;
//-*- mode:java; encoding:utf8n; coding:utf-8 -*-
// vim:set fileencoding=utf-8:
//@homepage@
import java.awt.*;
import javax.swing.*;

public class TranslucentMenuTest2 {
  private final JComponent tree = new JTree();
  public JComponent makeUI() {
    JPanel p = new JPanel(new BorderLayout());
    p.add(new JScrollPane(tree));
    return p;
  }
  private static JMenuBar makeMenuBar() {
    JMenu sub = new TransparentMenu("Test");
    sub.add(new JMenuItem("Undo aaaaaaaaaaaaaaaaaaaaaa"));
    sub.add(new JMenuItem("Redo"));

    JMenu menu = new TransparentMenu("Edit");
    menu.add(sub);
    menu.addSeparator();
    menu.add(new JMenuItem("Cut"));
    menu.add(new JMenuItem("Copy"));
    menu.add(new JMenuItem("Paste"));
    menu.add(new JMenuItem("Delete"));

    JMenuBar bar = new JMenuBar();
    bar.add(menu);
    return bar;
  }

  public static void main(String[] args) {
    EventQueue.invokeLater(new Runnable() {
      @Override public void run() {
        createAndShowGUI();
      }
    });
  }
  public static void createAndShowGUI() {
    PopupFactory.setSharedInstance(new TranslucentPopupFactory());

    JFrame f = new JFrame();
    f.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
    f.getContentPane().add(new TranslucentMenuTest2().makeUI());
    f.setJMenuBar(makeMenuBar());
    f.setSize(320, 240);
    f.setLocationRelativeTo(null);
    f.setVisible(true);
  }
}
//http://terai.xrea.jp/Swing/TranslucentPopupMenu.html
class TranslucentPopupMenu extends JPopupMenu {
  private static final Color ALPHA_ZERO = new Color(0, true);
  private static final Color POPUP_BACK = new Color(250,250,250,200);
  private static final Color POPUP_LEFT = new Color(230,230,230,200);
  private static final int LEFT_WIDTH = 24;
  @Override public boolean isOpaque() {
    return false;
  }
  @Override public Component add(Component c) {
    if(c instanceof JComponent) {
      ((JComponent)c).setOpaque(false);
    }
    return c;
  }
  @Override public JMenuItem add(JMenuItem menuItem) {
    menuItem.setOpaque(false);
    return super.add(menuItem);
  }
  @Override protected void paintComponent(Graphics g) {
    Graphics2D g2 = (Graphics2D)g.create();
//*
    g2.setPaint(POPUP_LEFT);
    g2.fillRect(0,0,LEFT_WIDTH,getHeight());
    g2.setPaint(POPUP_BACK);
    g2.fillRect(LEFT_WIDTH,0,getWidth(),getHeight());
/*/
    g2.setPaint(new Color(200,200,200,100));
    g2.fillRect(0,0,getWidth(),getHeight());
//*/
    g2.dispose();
  }
}

class TransparentMenu extends JMenu {
  public TransparentMenu(String title) {
    super(title);
  }
  //http://bugs.sun.com/bugdatabase/view_bug.do?bug_id=4688783
  private JPopupMenu popupMenu;
  private void ensurePopupMenuCreated() {
    if (popupMenu == null) {
      this.popupMenu = new TranslucentPopupMenu();
      popupMenu.setInvoker(this);
      popupListener = createWinListener(popupMenu);
    }
  }
  @Override public JPopupMenu getPopupMenu() {
    ensurePopupMenuCreated();
    return popupMenu;
  }
  @Override public JMenuItem add(JMenuItem menuItem) {
    ensurePopupMenuCreated();
    menuItem.setOpaque(false);
    return popupMenu.add(menuItem);
  }
  @Override public Component add(Component c) {
    ensurePopupMenuCreated();
    if(c instanceof JComponent) {
      ((JComponent)c).setOpaque(false);
    }
    popupMenu.add(c);
    return c;
  }
  @Override public void addSeparator() {
    ensurePopupMenuCreated();
    popupMenu.addSeparator();
  }
  @Override public void insert(String s, int pos) {
    if (pos < 0) {
      throw new IllegalArgumentException("index less than zero.");
    }
    ensurePopupMenuCreated();
    popupMenu.insert(new JMenuItem(s), pos);
  }
  @Override public JMenuItem insert(JMenuItem mi, int pos) {
    if (pos < 0) {
      throw new IllegalArgumentException("index less than zero.");
    }
    ensurePopupMenuCreated();
    popupMenu.insert(mi, pos);
    return mi;
  }
  @Override public void insertSeparator(int index) {
    if (index < 0) {
      throw new IllegalArgumentException("index less than zero.");
    }
    ensurePopupMenuCreated();
    popupMenu.insert( new JPopupMenu.Separator(), index );
  }
  @Override public boolean isPopupMenuVisible() {
    ensurePopupMenuCreated();
    return popupMenu.isVisible();
  }
}

//<a href="http://today.java.net/pub/a/today/2008/03/18/translucent-and-shaped-swing-windows.html">
//  Translucent and Shaped Swing Windows | Java.net
//</a>
class TranslucentPopupFactory extends PopupFactory {
  @Override public Popup getPopup(Component owner, Component contents, int x, int y) throws IllegalArgumentException {
    return new TranslucentPopup(owner, contents, x, y);
  }
}

class TranslucentPopup extends Popup {
  private JWindow popupWindow;

  public TranslucentPopup(Component owner, Component contents, int ownerX, int ownerY) {
    // create a new heavyweight window
    this.popupWindow = new JWindow();
    popupWindow.setBackground(new Color(0, true));
    // mark the popup with partial opacity
    //com.sun.awt.AWTUtilities.setWindowOpacity(popupWindow, (contents instanceof JToolTip) ? 0.8f : 0.95f);
    //popupWindow.setOpacity(.5f);
    // determine the popup location
    popupWindow.setLocation(ownerX, ownerY);
    // add the contents to the popup
    popupWindow.getContentPane().add(contents, BorderLayout.CENTER);
    contents.invalidate();
    //JComponent parent = (JComponent) contents.getParent();
    // set the shadow border
    //parent.setBorder(new ShadowPopupBorder());
  }

  @Override public void show() {
    this.popupWindow.setVisible(true);
    this.popupWindow.pack();
    // mark the window as non-opaque, so that the
    // shadow border pixels take on the per-pixel
    // translucency
    //com.sun.awt.AWTUtilities.setWindowOpaque(this.popupWindow, false);
  }

  @Override public void hide() {
    this.popupWindow.setVisible(false);
    this.popupWindow.removeAll();
    this.popupWindow.dispose();
  }
}
