//package example;
//-*- mode:java; encoding:utf-8 -*-
// vim:set fileencoding=utf-8:
//@homepage@
import java.awt.*;
import java.awt.event.*;
import java.util.*;
import java.util.List;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.table.*;
import javax.swing.text.*;
import javax.swing.plaf.basic.*;

public class TablePaginationLoadingTest {
    private static final LinkViewRadioButtonUI ui = new LinkViewRadioButtonUI();
    private static final int LR_PAGE_SIZE = 5;
    private static final int ITEMS_PER_PAGE = 100;
    private int currentPageIndex = 1;

    private final Box box = Box.createHorizontalBox();
    private final String[] columnNames = {"Name", "Age"};
    private final DefaultTableModel model = new DefaultTableModel(null, columnNames) {
        @Override public Class<?> getColumnClass(int column) {
            return (column==1)?Integer.class:String.class;
        }
    };
    private final TableRowSorter<TableModel> sorter = new TableRowSorter<TableModel>(model);
    private final JTable table = new JTable(model);
    public JComponent makeUI() {
        table.setFillsViewportHeight(true);
        table.setIntercellSpacing(new Dimension());
        table.setShowGrid(false);
        table.putClientProperty("terminateEditOnFocusLost", Boolean.TRUE);
        table.setRowSorter(sorter);

        JPanel p = new JPanel(new BorderLayout());
        p.add(box, BorderLayout.NORTH);
        p.add(new JScrollPane(table));
        p.add(button, BorderLayout.SOUTH);
        return p;
    }
    private final JButton button = new JButton(new AbstractAction("get") {
        @Override public void actionPerformed(ActionEvent e) {
            table.setEnabled(false);
            button.setEnabled(false);
            SwingWorker<String, List<MyBean>> worker = new SwingWorker<String, List<MyBean>>() {
                private int max = 2013;
                @Override public String doInBackground() {
                    int current = 1;
                    // Hibernate: not tested
                    // SessionFactory factory = new Configuration().configure().buildSessionFactory();
                    // Session session = factory.openSession();
                    // Criteria criteria = session.createCriteria(MyBean.class);
                    // criteria.setMaxResults(ITEMS_PER_PAGE);
                    int c = max/ITEMS_PER_PAGE;
                    int i = 0;
                    while(i<c && !isCancelled()) {
                        // Hibernate: not tested
                        // criteria.setFirstResult(current);
                        // List<MyBean> result = criteria.list();
                        // make dummy list
                        List<MyBean> result = loadMyBeanList(current, ITEMS_PER_PAGE);
                        publish(result);
                        current+=ITEMS_PER_PAGE;
                        i++;
                    }
                    int m = max%ITEMS_PER_PAGE;
                    if(m>0) {
                        List<MyBean> result = loadMyBeanList(current, m);
                        publish(result);
                    }
                    return "Done";
                }
                private List<MyBean> loadMyBeanList(int current, int size) {
                    try{
                        Thread.sleep(500); //dummy
                    }catch(Exception ex) {
                        ex.printStackTrace();
                    }
                    List<MyBean> result = new ArrayList<MyBean>(size);
                    int i = current;
                    while(i<current+size) {
                        result.add(new MyBean("aaa", i));
                        i++;
                    }
                    return result;
                }
                @Override protected void process(List<List<MyBean>> chunks) {
                    for(List<MyBean> list : chunks) {
                        for(MyBean mb: list) {
                            model.addRow(new Object[] {mb.getName(), mb.getAge()});
                        }
                    }
                    initLinkBox(currentPageIndex);
                }
                @Override public void done() {
                    String text = null;
                    if(isCancelled()) {
                        text = "Cancelled";
                    }else{
                        try {
                            text = get();
                        }catch(Exception ex) {
                            ex.printStackTrace();
                            text = "Exception";
                        }
                    }
                    table.setEnabled(true);
                    button.setEnabled(true);
                }
            };
            worker.execute();
        }
    });

    private void initLinkBox(int target) {
        //assert target>0;
        this.currentPageIndex = target;
        sorter.setRowFilter(new RowFilter<TableModel,Integer>() {
            @Override public boolean include(Entry<? extends TableModel, ? extends Integer> entry) {
                int ti = currentPageIndex-1;
                int ei = entry.getIdentifier();
                return ti*ITEMS_PER_PAGE<=ei && ei<ti*ITEMS_PER_PAGE+ITEMS_PER_PAGE;
            }
        });

        int startPageIndex = currentPageIndex-LR_PAGE_SIZE;
        if(startPageIndex<=0) startPageIndex = 1;

//#if 0 //BUG
        //int maxPageIndex = (model.getRowCount()/ITEMS_PER_PAGE)+1;
//#else
        /* "maxPageIndex" gives one blank page if the module of the division is not zero.
         *   pointed out by erServi
         * e.g. rowCount=100, maxPageIndex=100
         */
        int rowCount = model.getRowCount();
        int v = rowCount%ITEMS_PER_PAGE==0 ? 0 : 1;
        int maxPageIndex = rowCount/ITEMS_PER_PAGE + v;
//#endif
        int endPageIndex = currentPageIndex+LR_PAGE_SIZE-1;
        if(endPageIndex>maxPageIndex) endPageIndex = maxPageIndex;

        box.removeAll();
        ArrayList<JRadioButton> paginationButtons = new ArrayList<JRadioButton>();
        if(currentPageIndex>1) {
            paginationButtons.add(makePNRadioButton(1, "First"));
            paginationButtons.add(makePNRadioButton(currentPageIndex-1, "Prev"));
        }
        if(startPageIndex<endPageIndex) {
            for(int i=startPageIndex;i<=endPageIndex;i++) {
                paginationButtons.add(makeRadioButton(currentPageIndex, i));
            }
        }
        if(currentPageIndex<maxPageIndex) {
            paginationButtons.add(makePNRadioButton(currentPageIndex+1, "Next"));
            paginationButtons.add(makePNRadioButton(maxPageIndex, "Last"));
        }

        ButtonGroup bg = new ButtonGroup();
        box.add(Box.createHorizontalGlue());
        for(JRadioButton r:paginationButtons) {
            box.add(r); bg.add(r);
        }
        box.add(Box.createHorizontalGlue());
        paginationButtons.clear();
        box.revalidate();
        box.repaint();
    }
    private JRadioButton makeRadioButton(final int current, final int target) {
        JRadioButton radio = new JRadioButton(String.valueOf(target)) {
            @Override protected void fireStateChanged() {
                ButtonModel model = getModel();
                if(!model.isEnabled()) {
                    setForeground(Color.GRAY);
                }else if(model.isPressed() && model.isArmed()) {
                    setForeground(Color.GREEN);
                }else if(model.isSelected()) {
                    setForeground(Color.RED);
                }
                super.fireStateChanged();
            }
        };
        radio.setForeground(Color.BLUE);
        radio.setUI(ui);
        if(target==current) {
            radio.setSelected(true);
        }
        radio.addActionListener(new ActionListener() {
            @Override public void actionPerformed(ActionEvent e) {
                initLinkBox(target);
            }
        });
        return radio;
    }
    private JRadioButton makePNRadioButton(final int target, String title) {
        JRadioButton radio = new JRadioButton(title);
        radio.setForeground(Color.BLUE);
        radio.setUI(ui);
        radio.addActionListener(new ActionListener() {
            @Override public void actionPerformed(ActionEvent e) {
                initLinkBox(target);
            }
        });
        return radio;
    }
    public static void main(String[] args) {
        EventQueue.invokeLater(new Runnable() {
            @Override public void run() {
                createAndShowGUI();
            }
        });
    }
    public static void createAndShowGUI() {
        try{
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        }catch(Exception e) {
            e.printStackTrace();
        }
        JFrame frame = new JFrame("@title@");
        frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        frame.getContentPane().add(new TablePaginationLoadingTest().makeUI());
        frame.setSize(320, 240);
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);
    }
}

class LinkViewRadioButtonUI extends BasicRadioButtonUI {
//     private final static LinkViewRadioButtonUI radioButtonUI = new LinkViewRadioButtonUI();
//     private boolean defaults_initialized = false;
//     public static ComponentUI createUI(JComponent b) {
//         return radioButtonUI;
//     }
//     @Override protected void installDefaults(AbstractButton b){
//         super.installDefaults(b);
//         if(!defaults_initialized) {
//             icon = null; //UIManager.getIcon(getPropertyPrefix() + "icon");
//             defaults_initialized = true;
//         }
//     }
//     @Override protected void uninstallDefaults(AbstractButton b){
//         super.uninstallDefaults(b);
//         defaults_initialized = false;
//     }
    @Override public Icon getDefaultIcon() {
        return null;
    }
    private static Dimension size = new Dimension();
    private static Rectangle viewRect = new Rectangle();
    private static Rectangle iconRect = new Rectangle();
    private static Rectangle textRect = new Rectangle();
    @Override public synchronized void paint(Graphics g, JComponent c) {
        AbstractButton b = (AbstractButton) c;
        ButtonModel model = b.getModel();
        Font f = c.getFont();
        g.setFont(f);
        FontMetrics fm = c.getFontMetrics(f);

        Insets i = c.getInsets();
        size = b.getSize(size);
        viewRect.x = i.left;
        viewRect.y = i.top;
        viewRect.width = size.width - (i.right + viewRect.x);
        viewRect.height = size.height - (i.bottom + viewRect.y);
        iconRect.x = iconRect.y = iconRect.width = iconRect.height = 0;
        textRect.x = textRect.y = textRect.width = textRect.height = 0;

        String text = SwingUtilities.layoutCompoundLabel(
            c, fm, b.getText(), null, //altIcon != null ? altIcon : getDefaultIcon(),
            b.getVerticalAlignment(), b.getHorizontalAlignment(),
            b.getVerticalTextPosition(), b.getHorizontalTextPosition(),
            viewRect, iconRect, textRect,
            0); //b.getText() == null ? 0 : b.getIconTextGap());

        if(c.isOpaque()) {
            g.setColor(b.getBackground());
            g.fillRect(0,0, size.width, size.height);
        }
        if(text==null) return;
//         // Changing Component State During Painting (an infinite repaint loop)
//         // pointed out by Peter
//         // -note: http://today.java.net/pub/a/today/2007/08/30/debugging-swing.html#changing-component-state-during-the-painting
//         //b.setForeground(Color.BLUE);
//         if(!model.isEnabled()) {
//             //b.setForeground(Color.GRAY);
//         }else if(model.isPressed() && model.isArmed() || model.isSelected()) {
//             //b.setForeground(Color.BLACK);
//         }else if(b.isRolloverEnabled() && model.isRollover()) {

        g.setColor(b.getForeground());
        if(!model.isSelected() && !model.isPressed() && !model.isArmed()
           && b.isRolloverEnabled() && model.isRollover()) {
            g.drawLine(viewRect.x,                viewRect.y+viewRect.height,
                       viewRect.x+viewRect.width, viewRect.y+viewRect.height);
        }
        View v = (View) c.getClientProperty(BasicHTML.propertyKey);
        if(v!=null) {
            v.paint(g, textRect);
        }else{
            paintText(g, b, textRect, text);
        }
    }
}
class MyBean {
    private String name;
    private int age;
    public MyBean() {
        name = "empty";
        age = -1;
    }
    public MyBean(String name, int age) {
        this.name = name;
        this.age = age;
    }
    public String getName() {
        return name;
    }
    public int getAge() {
        return age;
    }
}
